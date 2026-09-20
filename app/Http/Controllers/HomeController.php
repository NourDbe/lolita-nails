<?php
namespace App\Http\Controllers;
use App\Models\GalleryItem;
use App\Models\Review;
use App\Models\Reel;
use App\Models\Service;
use App\Models\SiteSetting;
class HomeController extends Controller {
public function index(){ return view('home',[
'services'=>Service::where('is_active',true)->orderBy('sort_order')->take(8)->get(),
'gallery'=>GalleryItem::orderByDesc('is_featured')->orderBy('sort_order')->get(),
'reviews'=>Review::where('is_published',true)->orderBy('sort_order')->get(),
'reels'=>Reel::where('is_published',true)->orderBy('sort_order')->get(),
'site'=>SiteSetting::allKeyed(),
]); }
}
