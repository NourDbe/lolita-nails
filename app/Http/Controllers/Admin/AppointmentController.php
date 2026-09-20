<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
class AppointmentController extends Controller {
 public function index(Request $request){
   $query=Appointment::with('service')->orderByDesc('appointment_date')->orderBy('appointment_time');
   if($request->filled('status') && in_array($request->status,['pending','confirmed','completed','cancelled'],true)) $query->where('status',$request->status);
   if($request->filled('date')) $query->whereDate('appointment_date',$request->date);
   if($request->filled('q')) { $q=$request->q; $query->where(fn($x)=>$x->where('client_name','like',"%$q%")->orWhere('phone','like',"%$q%")); }
   return view('admin.appointments.index',['appointments'=>$query->paginate(20)->withQueryString()]);
 }
 public function updateStatus(Request $request, Appointment $appointment){ $data=$request->validate(['status'=>'required|in:pending,confirmed,completed,cancelled']); $appointment->update($data); return back()->with('success','تم تحديث حالة الحجز.'); }
 public function destroy(Appointment $appointment){ $appointment->delete(); return back()->with('success','تم حذف الحجز.'); }
}
