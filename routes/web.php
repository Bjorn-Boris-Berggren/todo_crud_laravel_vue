<?php

/**
 *|--------------------------------------------------------------------------
 *| Web Routes
 *|--------------------------------------------------------------------------
 *|
 *| Here is where you can register web routes for your application. These
 *| routes are loaded by the RouteServiceProvider within a group which
 *| contains the "web" middleware group. Enjoy building your API!
 *|
 */

Route::get('/', 'HomeController@showMainPage');
Route::get('/home', 'HomeController@showMainPage')->name('home');
