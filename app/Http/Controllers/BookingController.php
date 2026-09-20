<?php
namespace App\Http\Controllers;
use App\Models\Appointment;
use App\Models\DayOff;
use App\Models\Service;
use App\Models\WorkingHour;
use App\Models\SiteSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
class BookingController extends Controller {
 public function create(){ return view('booking',['services'=>Service::where('is_active',true)->orderBy('sort_order')->get(),'site'=>SiteSetting::allKeyed()]); }
 public function availability(Request $request)
{
    $validated = $request->validate([
        'date' => ['required', 'date', 'after_or_equal:today'],
        'service_id' => ['required', 'exists:services,id'],
    ]);

    $date = Carbon::parse($validated['date'])->startOfDay();

    // 1) Check if this date is a day off
    $isDayOff = DayOff::whereDate('date', $date)->exists();

    if ($isDayOff) {
        return response()->json([
            'slots' => [],
            'message' => 'هذا اليوم عطلة.',
        ]);
    }

    // 2) Get working hours for selected weekday
    $workingHour = WorkingHour::where(
        'day_of_week',
        $date->dayOfWeek
    )->first();

    if (!$workingHour || $workingHour->is_closed) {
        return response()->json([
            'slots' => [],
            'message' => 'لا يوجد دوام في هذا اليوم.',
        ]);
    }

    // 3) Get service duration
    $service = Service::select('id', 'duration_minutes')
        ->findOrFail($validated['service_id']);

    $duration = max(15, (int) $service->duration_minutes);

    // 4) Build working day start/end
    $dayStart = Carbon::parse(
        $validated['date'] . ' ' . $workingHour->opens_at
    );

    $dayEnd = Carbon::parse(
        $validated['date'] . ' ' . $workingHour->closes_at
    );

    // 5) Load ALL existing appointments for this day in one query
    $appointments = Appointment::query()
        ->whereDate('appointment_date', $date)
        ->whereNotIn('status', ['cancelled'])
        ->with('service:id,duration_minutes')
        ->get([
            'id',
            'service_id',
            'appointment_time',
        ]);

    // Convert existing appointments to occupied time ranges
    $occupiedRanges = $appointments->map(function ($appointment) use ($validated) {

        $appointmentStart = Carbon::parse(
            $validated['date'] . ' ' . $appointment->appointment_time
        );

        $appointmentDuration = max(
            15,
            (int) ($appointment->service?->duration_minutes ?? 60)
        );

        $appointmentEnd = $appointmentStart
            ->copy()
            ->addMinutes($appointmentDuration);

        return [
            'start' => $appointmentStart,
            'end' => $appointmentEnd,
        ];
    });

    // 6) Generate available slots
    $slots = [];

    // Slot interval: every 30 minutes
    $cursor = $dayStart->copy();

    while (true) {

        $slotStart = $cursor->copy();
        $slotEnd = $slotStart->copy()->addMinutes($duration);

        // Stop when service no longer fits before closing time
        if ($slotEnd->gt($dayEnd)) {
            break;
        }

        $hasConflict = $occupiedRanges->contains(
            function ($range) use ($slotStart, $slotEnd) {

                return $slotStart->lt($range['end'])
                    && $slotEnd->gt($range['start']);
            }
        );

        if (!$hasConflict) {
            $slots[] = $slotStart->format('H:i');
        }

        $cursor->addMinutes(30);
    }

    return response()->json([
        'slots' => $slots,
    ]);
}
 public function store(Request $request){
   $data=$request->validate([
     'service_id'=>'required|exists:services,id','client_name'=>'required|string|max:100','phone'=>'required|string|max:30',
     'appointment_date'=>'required|date|after_or_equal:today','appointment_time'=>'required|date_format:H:i','notes'=>'nullable|string|max:500',
     'booking_policy'=>'accepted'
   ],['booking_policy.accepted'=>'يرجى الموافقة على سياسة الحجز قبل التأكيد.']);
   unset($data['booking_policy']);
   $exists=Appointment::whereDate('appointment_date',$data['appointment_date'])->where('appointment_time',$data['appointment_time'].':00')->whereNotIn('status',['cancelled'])->exists();
   if($exists) return back()->withInput()->withErrors(['appointment_time'=>'هذا الموعد حُجز للتو. اختاري وقتاً آخر.']);
   $data['status']='pending'; $appointment=Appointment::create($data);
   return redirect()->route('booking.success')->with('appointment_id',$appointment->id);
 }
 public function success(){
   $appointment=null;
   if(session('appointment_id')) $appointment=Appointment::with('service')->find(session('appointment_id'));
   return view('booking-success',compact('appointment'));
 }
}
