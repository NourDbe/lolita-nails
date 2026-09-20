<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
 public function index(){
   $today=Appointment::with('service')->whereDate('appointment_date',today())->orderBy('appointment_time')->get();
   $weekStart=now()->startOfWeek(); $weekEnd=now()->endOfWeek();
   $week=Appointment::with('service')->whereBetween('appointment_date',[$weekStart,$weekEnd])->whereNotIn('status',['cancelled'])->get();
   $expectedRevenue=$week->sum(fn($a)=>(float)($a->service->price??0));
   $topService=Appointment::select('service_id',DB::raw('COUNT(*) as total'))->whereNotIn('status',['cancelled'])->groupBy('service_id')->orderByDesc('total')->with('service')->first();
   $repeatClients=Appointment::select('phone','client_name',DB::raw('COUNT(*) as total'))->whereNotIn('status',['cancelled'])->groupBy('phone','client_name')->having('total','>',1)->orderByDesc('total')->limit(5)->get();
   return view('admin.dashboard',[
     'today'=>$today,
     'pending'=>Appointment::where('status','pending')->count(),
     'confirmed'=>Appointment::where('status','confirmed')->count(),
     'services'=>Service::where('is_active',true)->count(),
     'weekCount'=>$week->count(),
     'expectedRevenue'=>$expectedRevenue,
     'topService'=>$topService?->service?->name_ar,
     'repeatClients'=>$repeatClients,
   ]);
 }
}
