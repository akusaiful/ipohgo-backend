<?php

use App\Http\Controllers\FirebasePushController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Kreait\Laravel\Firebase\Facades\Firebase;

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
Route::get('/intro','LandingpageController@index');
Route::get('/', 'HomeController@index');


Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Route::get('/password', function(){
//     echo bcrypt('j4mb#Nxx');
// });

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

Route::get('/privacy', [HomeController::class, 'privacy']);

// Logs
Route::get(config('admin.admin_route_prefix').'/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard','system_log_view'])->name('admin.logs');

Route::get('/install','InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
Route::get('/install/environment','InstallerController@redirectToWizard')->name('LaravelInstaller::environment');

Route::post('/save-device-token', [FirebasePushController::class, 'setToken'])->name('save-push-notification-token');
Route::post('/send-push-notification', [FirebasePushController::class, 'sendPushNotification'])->name('send.push-notification');

Route::get('/delete', [HomeController::class, 'delete']);
Route::post('/delete', [HomeController::class, 'deleteAction']);
