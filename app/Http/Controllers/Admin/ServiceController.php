<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ServiceController extends Controller {
 public function index(){ return view('admin.services.index',['services'=>Service::orderBy('sort_order')->get()]); }
 public function store(Request $request){ $data=$this->validated($request); if($request->hasFile('image_upload')) $data['image']='/storage/'.$request->file('image_upload')->store('services','public'); Service::create($data); return back()->with('success','تمت إضافة الخدمة.'); }
 public function update(Request $request, Service $service){ $data=$this->validated($request); if($request->hasFile('image_upload')){ $this->deletePublic($service->image); $data['image']='/storage/'.$request->file('image_upload')->store('services','public'); } $service->update($data); return back()->with('success','تم تحديث الخدمة.'); }
 public function destroy(Service $service){ $this->deletePublic($service->image); $service->delete(); return back()->with('success','تم حذف الخدمة.'); }
 private function validated(Request $request):array { $data=$request->validate(['name_ar'=>'required|max:100','name_en'=>'required|max:100','description_ar'=>'nullable|max:500','description_en'=>'nullable|max:500','price'=>'required|numeric|min:0','duration_minutes'=>'required|integer|min:15|max:480','image'=>'nullable|max:255','image_upload'=>'nullable|image|max:8192','sort_order'=>'nullable|integer','is_active'=>'nullable|boolean']); unset($data['image_upload']); $data['is_active']=$request->boolean('is_active'); $data['sort_order']=$data['sort_order']??0; return $data; }
 private function deletePublic(?string $path): void { if($path && str_starts_with($path,'/storage/')) Storage::disk('public')->delete(substr($path,9)); }
}
