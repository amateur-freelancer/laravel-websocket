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

Route::group(['namespace' => 'Api'], function() {
    Route::get('dashboards', 'DashboardController@index');
    Route::get('dashboards/{dashboard}', 'DashboardController@show');
    Route::post('dashboards', 'DashboardController@store');
    Route::put('dashboards/{dashboard}', 'DashboardController@update');
    Route::delete('dashboards/{code}', 'DashboardController@destroy');

    Route::post('messages/publish', 'MessageController@publish');
    Route::post('messages/bulk_publish', 'MessageController@bulk_publish');
});

Route::post('/register', 'AuthController@register');
Route::post('/login', 'AuthController@login');

Route::middleware('jwt.auth')->group(function(){
    Route::get('logout', 'AuthController@logout');
    Route::post('password', 'AuthController@password');
    Route::post('/users', 'UsersController@create');
    Route::get('/users', 'UsersController@list');
    Route::get('/users/{id}', 'UsersController@read');
    Route::put('/users/{id}', 'UsersController@update');
    Route::delete('/users/{id}', 'UsersController@delete');
});
