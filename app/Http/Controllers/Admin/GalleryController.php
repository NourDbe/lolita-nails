<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class GalleryController extends Controller {
 public function index(){ return view('admin.gallery.index',['items'=>GalleryItem::orderBy('sort_order')->get()]); }
 public function store(Request $request){ $data=$request->validate(['title'=>'nullable|max:120','image'=>'required|image|max:8192','category'=>'nullable|max:50','sort_order'=>'nullable|integer|min:0','is_featured'=>'nullable|boolean']); $data['image']='/storage/'.$request->file('image')->store('gallery','public'); $data['is_featured']=$request->boolean('is_featured'); $data['sort_order']=$data['sort_order']??0; GalleryItem::create($data); return back()->with('success','تمت إضافة الصورة.'); }
 public function update(Request $request, GalleryItem $gallery){ $data=$request->validate(['title'=>'nullable|max:120','image'=>'nullable|image|max:8192','category'=>'nullable|max:50','sort_order'=>'nullable|integer|min:0','is_featured'=>'nullable|boolean']); if($request->hasFile('image')){ $this->deletePublic($gallery->image); $data['image']='/storage/'.$request->file('image')->store('gallery','public'); } $data['is_featured']=$request->boolean('is_featured'); $gallery->update($data); return back()->with('success','تم تحديث الصورة.'); }
 public function destroy(GalleryItem $gallery){ $this->deletePublic($gallery->image); $gallery->delete(); return back()->with('success','تم حذف الصورة.'); }
 private function deletePublic(?string $path): void { if($path && str_starts_with($path,'/storage/')) Storage::disk('public')->delete(substr($path,9)); }
}
