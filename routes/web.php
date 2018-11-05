<?php

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

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::get('/', 'DatabasesController@index');
    Route::get('/databases', 'DatabasesController@index');
    Route::get('/users', 'UsersController@index');
});

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/admin', 'Admin\DatabasesController@index');

Route::get('/home', 'HomeController@index')->name('home');
