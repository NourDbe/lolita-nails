<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\DayOff;
use App\Models\WorkingHour;
use Illuminate\Http\Request;
class ScheduleController extends Controller {
 public function index(){ return view('admin.schedule.index',['hours'=>WorkingHour::orderBy('day_of_week')->get()->keyBy('day_of_week'),'daysOff'=>DayOff::orderBy('date')->get()]); }
 public function saveHours(Request $r){ $rows=$r->validate(['hours'=>'required|array','hours.*.opens_at'=>'nullable','hours.*.closes_at'=>'nullable','hours.*.is_closed'=>'nullable']); foreach($rows['hours'] as $day=>$row){ WorkingHour::updateOrCreate(['day_of_week'=>(int)$day],['opens_at'=>$row['opens_at']??'09:00','closes_at'=>$row['closes_at']??'18:00','is_closed'=>isset($row['is_closed'])]); } return back()->with('success','تم تحديث ساعات العمل.'); }
 public function addDayOff(Request $r){ $d=$r->validate(['date'=>'required|date|unique:days_off,date','reason'=>'nullable|max:200']); DayOff::create($d); return back()->with('success','تمت إضافة يوم الإجازة.'); }
 public function deleteDayOff(DayOff $dayOff){ $dayOff->delete(); return back()->with('success','تم حذف يوم الإجازة.'); }
}
