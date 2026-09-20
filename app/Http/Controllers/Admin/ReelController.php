<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Reel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ReelController extends Controller {
 public function index(){ return view('admin.reels.index',['reels'=>Reel::orderBy('sort_order')->get()]); }
 public function store(Request $request){ $data=$request->validate(['title'=>'nullable|max:120','label'=>'nullable|max:80','video'=>'required|file|mimes:mp4,mov,webm|max:51200','poster'=>'nullable|image|max:5120','sort_order'=>'nullable|integer|min:0','is_published'=>'nullable|boolean']); $data['video']='/storage/'.$request->file('video')->store('reels','public'); if($request->hasFile('poster')) $data['poster']='/storage/'.$request->file('poster')->store('reels/posters','public'); $data['label']=$data['label']??'Before → After'; $data['sort_order']=$data['sort_order']??0; $data['is_published']=$request->boolean('is_published'); Reel::create($data); return back()->with('success','تمت إضافة الريل.'); }
 public function update(Request $request, Reel $reel){ $data=$request->validate(['title'=>'nullable|max:120','label'=>'nullable|max:80','video'=>'nullable|file|mimes:mp4,mov,webm|max:51200','poster'=>'nullable|image|max:5120','sort_order'=>'nullable|integer|min:0','is_published'=>'nullable|boolean']); if($request->hasFile('video')){ $this->deletePublic($reel->video); $data['video']='/storage/'.$request->file('video')->store('reels','public'); } if($request->hasFile('poster')){ $this->deletePublic($reel->poster); $data['poster']='/storage/'.$request->file('poster')->store('reels/posters','public'); } $data['is_published']=$request->boolean('is_published'); $reel->update($data); return back()->with('success','تم تحديث الريل.'); }
 public function destroy(Reel $reel){ $this->deletePublic($reel->video); $this->deletePublic($reel->poster); $reel->delete(); return back()->with('success','تم حذف الريل.'); }
 private function deletePublic(?string $path): void { if($path && str_starts_with($path,'/storage/')) Storage::disk('public')->delete(substr($path,9)); }
}
