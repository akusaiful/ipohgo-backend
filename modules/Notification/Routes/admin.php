<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 7/1/2019
 * Time: 10:02 AM
 */
use Illuminate\Support\Facades\Route;

Route::get('/','NotificationController@index')->name('notification.admin.index');
Route::get('/create','NotificationController@create')->name('notification.admin.create');
Route::get('/edit/{id}', 'NotificationController@edit')->name('notification.admin.edit');
Route::post('/bulkEdit','NotificationController@bulkEdit')->name('notification.admin.bulkEdit');
Route::post('/store/{id}','NotificationController@store')->name('notification.admin.store');

// Route::get('/category','CategoryController@index')->name('news.admin.category.index');
// Route::get('/category/getForSelect2','CategoryController@getForSelect2')->name('news.admin.category.getForSelect2');
// Route::get('/category/edit/{id}','CategoryController@edit')->name('news.admin.category.edit');
// Route::post('/category/store/{id}','CategoryController@store')->name('news.admin.category.store');
// Route::post('/category/bulkEdit','CategoryController@bulkEdit')->name('news.admin.category.bulkEdit');

// Route::get('/tag','TagController@index')->name('news.admin.tag.index');
// Route::get('/tag/edit/{id}','TagController@edit')->name('news.admin.tag.edit');
// Route::post('/tag/store/{id}','TagController@store')->name('news.admin.tag.store');
// Route::post('/tag/bulkEdit','TagController@bulkEdit')->name('news.admin.tag.bulkEdit');