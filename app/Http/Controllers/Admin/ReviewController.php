<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
class ReviewController extends Controller {
 public function index(){ return view('admin.reviews.index',['reviews'=>Review::orderBy('sort_order')->get()]); }
 public function store(Request $request){ Review::create($this->data($request)); return back()->with('success','تمت إضافة التقييم.'); }
 public function update(Request $request, Review $review){ $review->update($this->data($request)); return back()->with('success','تم تحديث التقييم.'); }
 public function destroy(Review $review){ $review->delete(); return back()->with('success','تم حذف التقييم.'); }
 private function data(Request $r): array { $d=$r->validate(['client_name'=>'required|max:100','body'=>'required|max:800','rating'=>'required|integer|min:1|max:5','sort_order'=>'nullable|integer|min:0','is_published'=>'nullable|boolean']); $d['sort_order']=$d['sort_order']??0; $d['is_published']=$r->boolean('is_published'); return $d; }
}
