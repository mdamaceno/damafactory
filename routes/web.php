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
Route::group(['prefix' => '{locale}'], function () {
    Route::group([
        'prefix' => 'admin',
        'namespace' => 'Admin',
        'middleware' => ['install'],
    ], function () {
        Route::get('/', 'DatabasesController@index');

        Route::get('/databases', 'DatabasesController@index');
        Route::match(['get', 'post'], '/databases/edit', 'DatabasesController@edit');
        Route::match(['get', 'post'], '/databases/new', 'DatabasesController@create');

        Route::get('/users', 'UsersController@index');
        Route::match(['get', 'post'], '/users/edit', 'UsersController@edit');
        Route::match(['get', 'post'], '/users/new', 'UsersController@create');

        Route::get('/permissions', 'DBRolesController@index');
        Route::match(['get', 'post'], '/permissions/edit', 'DBRolesController@edit');
        Route::match(['get', 'post'], '/permissions/new', 'DBRolesController@create');

        Route::get('/auth-tokens', 'AuthTokensController@index');

        Route::get('/help', 'HelpsController@index');
    });
});

Route::group(['middleware' => ['install']], function () {
    // Authentication Routes...
    $this->get('login', 'Auth\LoginController@showLoginForm')->name('login');
    $this->post('login', 'Auth\LoginController@login');
    $this->post('logout', 'Auth\LoginController@logout')->name('logout');

    // Registration Routes...
    $this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register', 'Auth\RegisterController@register');

    // Password Reset Routes...
    $this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
    $this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    $this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
    $this->post('password/reset', 'Auth\ResetPasswordController@reset');

    Route::get('/logout', 'Auth\LoginController@logout');

    Route::get('/admin', 'Admin\DatabasesController@index');
});

Route::get('/', 'Admin\DatabasesController@index');
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/install', 'InstallController@index');
Route::post('/install', 'InstallController@create')->name('install');
