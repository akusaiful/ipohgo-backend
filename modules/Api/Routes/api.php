<?php

use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Route;
use Modules\Api\Controllers\DeviceController;
use Modules\Api\Controllers\TourController;
use Modules\Tour\Controllers\TourController as ControllersTourController;

use function Clue\StreamFilter\fun;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/* Config */

Route::get('configs', 'BookingController@getConfigs')->name('api.get_configs');
/* Service */
Route::get('services', 'SearchController@searchServices')->name('api.service-search');
Route::get('{type}/search', 'SearchController@search')->name('api.search2');
Route::get('{type}/detail/{id}', 'SearchController@detail')->name('api.detail');
Route::get('{type}/availability/{id}', 'SearchController@checkAvailability')->name('api.service.check_availability');
Route::get('boat/availability-booking/{id}', 'SearchController@checkBoatAvailability')->name('api.service.checkBoatAvailability');

/* Tour Featured */
Route::get('/tour/featured', [TourController::class, 'featured'])->name('api.tour.featured');
Route::get('/tour/like/{tour}', [TourController::class, 'like'])->name('api.tour.like');
Route::get('/tour/review/{tour}', [TourController::class, 'review'])->name('api.tour.review');
Route::get('/tour/review/list/{tour}', [TourController::class, 'reviewList'])->name('api.tour.review.list');
Route::get('/tour/popular', [TourController::class, 'popular'])->name('api.tour.popular');
Route::get('/tour/recently', [TourController::class, 'recently'])->name('api.tour.recently');
Route::get('/tour/view/category', [TourController::class, 'tourByCategory'])->name('api.tour.by.category');
Route::get('/tour/seen/{tour}', [TourController::class, 'updateView'])->name('api.tour.view.update');
Route::get('/review/random', [TourController::class, 'reviewRandom']);

Route::post('/device/register', [DeviceController::class, 'register']);
/**
 * List of tour scroll by user 
 */
Route::get('/tour/list', [TourController::class, 'list'])->name('api.tour.list');
Route::get('/tour/recommended', [TourController::class, 'recommended'])->name('api.tour.recommended');
Route::get('/tour/category', [TourController::class, 'category'])->name('api.tour.category');
// Route::get('/tour/search2', [TourController::class, 'search'])->name('api.tour.search');


Route::get('{type}/filters', 'SearchController@getFilters')->name('api.service.filter');
Route::get('{type}/form-search', 'SearchController@getFormSearch')->name('api.service.form');

Route::group(['middleware' => 'api'], function () {
    Route::post('{type}/write-review/{id}', 'ReviewController@writeReview')->name('api.service.write_review');
});


/* Layout HomePage */
Route::get('home-page', 'BookingController@getHomeLayout')->name('api.get_home_layout');

/* Register - Login */
Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::post('login', 'AuthController@login')->middleware(['throttle:login']);
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@me');
    Route::get('profile', 'AuthController@profile');
    Route::get('exists', 'AuthController@exists');
    Route::post('me', 'AuthController@updateUser');
    Route::post('update-profile', 'AuthController@updateProfile');
    Route::post('change-password', 'AuthController@changePassword');
});

/* User */
Route::group(['prefix' => 'user', 'middleware' => ['api']], function ($router) {
    Route::get('booking-history', 'UserController@getBookingHistory')->name("api.user.booking_history");
    
    Route::post('/wishlist', 'UserController@handleWishlist')->name("api.user.wishList.handle");
    Route::get('/wishlist', 'UserController@indexWishlist')->name("api.user.wishList.index");    
    Route::get('/tour-wishlist', 'UserController@tourWishlist')->name("api.user.tour.wishList.index");

    Route::post('/bookmark', 'UserController@handleBookmark')->name("api.user.bookmark.handle");
    Route::get('/bookmark', 'UserController@indexBookmark')->name("api.user.bookmark.index");    
    Route::get('/bookmark-list', 'UserController@bookmarkList')->name("api.user.bookmark.list");    
    
    Route::post('/permanently_delete', 'UserController@permanentlyDelete')->name("user.permanently.delete");    

});

/* Location */
Route::get('locations', 'LocationController@search')->name('api.location.search');
Route::get('location/{id}', 'LocationController@detail')->name('api.location.detail');

// Booking
Route::group(['prefix' => config('booking.booking_route_prefix')], function () {
    Route::post('/addToCart', 'BookingController@addToCart')->name("api.booking.add_to_cart");
    Route::post('/addEnquiry', 'BookingController@addEnquiry')->name("api.booking.add_enquiry");
    Route::post('/doCheckout', 'BookingController@doCheckout')->name('api.booking.doCheckout');
    Route::get('/confirm/{gateway}', 'BookingController@confirmPayment');
    Route::get('/cancel/{gateway}', 'BookingController@cancelPayment');
    Route::get('/{code}', 'BookingController@detail');
    Route::get('/{code}/thankyou', 'BookingController@thankyou')->name('booking.thankyou');
    Route::get('/{code}/checkout', 'BookingController@checkout');
    Route::get('/{code}/check-status', 'BookingController@checkStatusCheckout');
});

// Gateways
Route::get('/gateways', 'BookingController@getGatewaysForApi');

// News
Route::get('news', 'NewsController@search')->name('api.news.search');
Route::get('news/category', 'NewsController@category')->name('api.news.category');
Route::get('news/view/{news}', 'NewsController@viewUpdate')->name('api.news.view.update');
Route::get('news/{id}', 'NewsController@detail')->name('api.news.detail');


// News
Route::get('/notification', 'NotificationController@search')->name('api.notification.search');
Route::get('/notification/{id}', 'NotificationController@detail')->name('api.notification.detail');


// check like 
Route::get('/tour/ilike', 'TourController@checkLike');
Route::get('/bookmark/check', '\Modules\User\Controllers\UserBookmarkController@check');

/* Media */
Route::group(['prefix' => 'media', 'middleware' => 'auth:sanctum'], function () {
    Route::post('/store', 'MediaController@store')->name("api.media.store");
});

/** Place */

Route::get('/place', function () {
    for ($i = 1; $i <= 5; $i++) {
        $data['data'][] = [
            'state' => 'State ' . $i,
            'place name' => "Perak " . $i,
            'location' => 'Location',
            'latitute' => 1.4897441588618703,
            'longtitude' => 103.71417871574465,
            'description' => 'Description',
            'image-1' => 'https://images.unsplash.com/photo-1695653420644-ab3d6a039d53?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=4138&q=80',
            'image-2' => 'https://images.unsplash.com/photo-1695653420644-ab3d6a039d53?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=4138&q=80',
            'image-3' => 'https://images.unsplash.com/photo-1695653420644-ab3d6a039d53?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=4138&q=80',
            'loves' => 1,
            'comments count' => 10,
            'date' => '12-09-2023',
            'timestamp' => '1696230060'

        ];
    }
    return response()->json($data);
});

Route::get('/stream', function(){
    return response()->json(['total' => 10]);
});
