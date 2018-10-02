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

Route::post('user/register', 'APIRegisterController@register');
Route::post('user/login', 'APILoginController@login');

Route::group([
    'prefix' => 'v1/{db_name}',
    'namespace' => 'API\V1',
    'middleware' => ['jwt.auth'],
], function () {
    Route::get('/{table_name}', 'ApiController@getManyData');
    Route::get('/{table_name}/{id}', 'ApiController@getSingleData');
    Route::post('/{table_name}', 'ApiController@postData');
    Route::match(['put', 'patch'], '/{table_name}/{id}', 'ApiController@updateData');
    Route::match(['put', 'patch'], '/{table_name}', 'ApiController@updateFilteringData');
    Route::delete('/{table_name}/{id}', 'ApiController@deleteData');
    Route::delete('/{table_name}', 'ApiController@deleteFilteringData');

    Route::get('/', 'ApiController@getDatabaseInfo');
});
