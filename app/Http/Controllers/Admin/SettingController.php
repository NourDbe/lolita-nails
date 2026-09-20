<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
class SettingController extends Controller {
 public function index(){ return view('admin.settings.index',['settings'=>SiteSetting::allKeyed()]); }
 public function update(Request $r){ $data=$r->validate([
  'brand_name'=>'nullable|max:100','hero_eyebrow'=>'nullable|max:160','hero_title'=>'nullable|max:200','hero_subtitle'=>'nullable|max:500','location'=>'nullable|max:150','phone'=>'nullable|max:50','whatsapp'=>'nullable|max:50','instagram_url'=>'nullable|url|max:255','instagram_handle'=>'nullable|max:80','followers_label'=>'nullable|max:80','booking_policy'=>'nullable|max:2500','seo_title'=>'nullable|max:160','seo_description'=>'nullable|max:300','show_gallery'=>'nullable','show_reels'=>'nullable','show_reviews'=>'nullable','show_before_after'=>'nullable'
 ]); foreach(['brand_name','hero_eyebrow','hero_title','hero_subtitle','location','phone','whatsapp','instagram_url','instagram_handle','followers_label','booking_policy','seo_title','seo_description'] as $key) SiteSetting::put($key,$data[$key]??''); foreach(['show_gallery','show_reels','show_reviews','show_before_after'] as $key) SiteSetting::put($key,$r->boolean($key)?'1':'0'); return back()->with('success','تم حفظ إعدادات الموقع.'); }
}
