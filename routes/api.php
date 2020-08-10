<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');

Route::middleware('auth:api')->group(function (){
    Route::get('/logout', 'API\AuthController@logout');

    Route::get('/profile', 'API\UserController@show');
    Route::put('/profile', 'API\UserController@update');
    Route::patch('/profile', 'API\UserController@update');

    Route::get('/actions', 'API\ActionController@index');
    Route::post('/actions', 'API\ActionController@store');
    Route::get('/actions/{actionTypeId}', 'API\ActionController@show');
    Route::delete('/actions/{actionTypeId}', 'API\ActionController@destroy');

    Route::get('/report', 'API\ReportController@makeReport');
});
