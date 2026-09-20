<?php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ReelController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/language/{locale}', function (string $locale) {
    abort_unless(in_array($locale, ['ar', 'en'], true), 404);
    session(['locale' => $locale]);
    return redirect()->back();
})->name('language.switch');

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/book',[BookingController::class,'create'])->name('booking.create');
Route::get('/availability',[BookingController::class,'availability'])->name('booking.availability');
Route::post('/book',[BookingController::class,'store'])->name('booking.store');
Route::get('/booking/success',[BookingController::class,'success'])->name('booking.success');

Route::prefix('admin')->name('admin.')->group(function(){
 Route::middleware('guest')->group(function(){ Route::get('/login',[AuthController::class,'showLogin'])->name('login'); Route::post('/login',[AuthController::class,'login'])->name('login.submit'); });
 Route::middleware('auth')->group(function(){
  Route::post('/logout',[AuthController::class,'logout'])->name('logout');
  Route::get('/',[DashboardController::class,'index'])->name('dashboard');
  Route::get('/appointments',[AdminAppointmentController::class,'index'])->name('appointments.index');
  Route::patch('/appointments/{appointment}/status',[AdminAppointmentController::class,'updateStatus'])->name('appointments.status');
  Route::delete('/appointments/{appointment}',[AdminAppointmentController::class,'destroy'])->name('appointments.destroy');
  Route::get('/services',[AdminServiceController::class,'index'])->name('services.index');
  Route::post('/services',[AdminServiceController::class,'store'])->name('services.store');
  Route::patch('/services/{service}',[AdminServiceController::class,'update'])->name('services.update');
  Route::delete('/services/{service}',[AdminServiceController::class,'destroy'])->name('services.destroy');
  Route::get('/gallery',[GalleryController::class,'index'])->name('gallery.index');
  Route::post('/gallery',[GalleryController::class,'store'])->name('gallery.store');
  Route::patch('/gallery/{gallery}',[GalleryController::class,'update'])->name('gallery.update');
  Route::delete('/gallery/{gallery}',[GalleryController::class,'destroy'])->name('gallery.destroy');
  Route::get('/reels',[ReelController::class,'index'])->name('reels.index');
  Route::post('/reels',[ReelController::class,'store'])->name('reels.store');
  Route::patch('/reels/{reel}',[ReelController::class,'update'])->name('reels.update');
  Route::delete('/reels/{reel}',[ReelController::class,'destroy'])->name('reels.destroy');
  Route::get('/reviews',[ReviewController::class,'index'])->name('reviews.index');
  Route::post('/reviews',[ReviewController::class,'store'])->name('reviews.store');
  Route::patch('/reviews/{review}',[ReviewController::class,'update'])->name('reviews.update');
  Route::delete('/reviews/{review}',[ReviewController::class,'destroy'])->name('reviews.destroy');
  Route::get('/schedule',[ScheduleController::class,'index'])->name('schedule.index');
  Route::post('/schedule/hours',[ScheduleController::class,'saveHours'])->name('schedule.hours');
  Route::post('/schedule/days-off',[ScheduleController::class,'addDayOff'])->name('schedule.days-off.store');
  Route::delete('/schedule/days-off/{dayOff}',[ScheduleController::class,'deleteDayOff'])->name('schedule.days-off.destroy');
  Route::get('/settings',[SettingController::class,'index'])->name('settings.index');
  Route::post('/settings',[SettingController::class,'update'])->name('settings.update');
 });
});
