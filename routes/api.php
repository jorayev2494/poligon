<?php

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

Route::group(['prefix' => 'auth'], static function (): void {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::post('/refresh', 'AuthController@refresh');
    Route::post('/logout', 'AuthController@logout');
});

Route::group(['middleware' => 'auth:api'], static function (): void {
    Route::get('/profile', 'ProfileController@profile');
    Route::apiResource('/users', 'UserController');
    Route::post('/users/{id}/avatars', 'UserAvatarController');
});
