<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('user/register', 'API\RegisterController@register');
Route::post('user/login', 'API\LoginController@login');

Route::group([
    'prefix' => 'databases',
    'namespace' => 'API',
    'middleware' => ['jwt.auth', 'role.permission'],
], function () {
    Route::post('/', 'DatabasesController@insertDatabase');

    Route::group([
        'prefix' => '{db_name}',
    ], function () {
        Route::get('/{table_name}', 'DatabasesController@getManyData');
        Route::get('/{table_name}/{id}', 'DatabasesController@getSingleData');
        Route::post('/{table_name}', 'DatabasesController@postData');
        Route::match(['put', 'patch'], '/{table_name}/{id}', 'DatabasesController@updateData');
        Route::match(['put', 'patch'], '/{table_name}', 'DatabasesController@updateFilteringData');
        Route::delete('/{table_name}/{id}', 'DatabasesController@deleteData');
        Route::delete('/{table_name}', 'DatabasesController@deleteFilteringData');

        Route::get('/', 'DatabasesController@getDatabaseInfo');
    });
});
