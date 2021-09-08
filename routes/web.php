<?php

use App\Models\Subcategory;
use GuzzleHttp\Promise\Create;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\FraduController;
use App\Http\Controllers\SaveAdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontAdsController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SendMessageController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\AdminListingController;
use App\Http\Controllers\AdvertisementController;
use App\Http\Controllers\ChildcategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//admin

// Route::group(['prefix'=>'auth'],function(){
//     Route::resource('/category', [CategoryController::class]);
// });



Route::get('/home', function () {
    return view('home');
});
// Route::get('/auth', function () {
//     return view('backend.admin.index');
// });

Route::group(['prefix'=>'auth' , 'middleware'=>'admin'],function(){
    Route::resource('category', CategoryController::class);
    Route::resource('subcategory', SubcategoryController::class);
    Route::resource('childcategory', ChildcategoryController::class);

    Route::get('allads', [AdminListingController::class,'index'])->name('all.ads');

    Route::get('/reported-ads',[FraduController::class,'index'])->name('all.reported.ads');

});

// Route::get('/',[MenuController::class,'menu']);

Route::get('/', [FrontAdsController::class,'index']);

//Ads
Route::get('/ads/create',[AdvertisementController::class,'create'])->middleware('auth')->name('ads.create');
Route::post('/ads/store',[AdvertisementController::class,'store'])->middleware('auth')->name('ads.store');
Route::get('/ads',[AdvertisementController::class,'index'])->middleware('auth')->name('ads.index');
Route::get('/ads/{id}/edit',[AdvertisementController::class,'edit'])->middleware('auth')->name('ads.edit');
Route::put('/ads/{id}/update',[AdvertisementController::class,'update'])->name('ads.update')->middleware('auth');
Route::delete('/ads/{id}/delete',[AdvertisementController::class,'destroy'])->name('ads.destroy');

//profile
Route::get('/profile',[ProfileController::class,'index'])->middleware('auth')->name('profile');
Route::post('/profile',[ProfileController::class,'updateProfile'])->middleware('auth')->name('update.profile');

//user ads
Route::get('/ads/{userId}/view',[FrontendController::class,'viewUserAds'])->name('show.user.ads');

//frontend
Route::get('/product/{categorySlug}/{subcategorySlug}',[FrontendController::class,'findBasedOnSubcategory'])->name('subcategory.show');

Route::get('/product/{categorySlug}/{subcategorySlug}/{childcategorySlug}',[FrontendController::class,'findBasedOnChildcategory'])->name('childcategory.show');

Route::get(
    '/product/{categorySlug}',[FrontendController::class,'findBasedOnCategory'])->name('category.show');

Route::get('/products/{id}/{slug}',[FrontendController::class,'show'])->name('product.view');

//Message
Route::post('/send/message',[SendMessageController::class,'store'])->middleware('auth');

Route::get('/messages',[SendMessageController::class,'index'])->name('messages')->middleware('auth');;

Route::post('/start-conversation',[SendMessageController::class,'startConversation']);
Route::get('/users',[SendMessageController::class,'chatWithThisUser']);
Route::get('/message/user/{id}',[SendMessageController::class,'showMessages']);

//login with facebook
Route::get('auth/facebook', [SocialLoginController::class,'facebookRedirect']);
Route::get('auth/facebook/callback', [SocialLoginController::class,'loginWithFacebook']);

//Save ad
Route::post('/ad/save',[SaveAdController::class,'saveAd']);
Route::post('/ad/remove',[SaveAdController::class,'removeAd'])->name('ad.remove');
Route::get('/saved-ads',[SaveAdController::class,'getMyads'])->name('saved.ad');

//report this ad
Route::post('/report-this-ad',[FraduController::class,'store'])->name('report.ad');

//pending ads
Route::get('/ad-pending',[AdvertisementController::class,'pendingAds'])->name('pending.ad');
