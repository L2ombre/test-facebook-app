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

Route::get('/', function () {
    return redirect('home');
});

Route::get('auth/{provider}', 'Auth\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');
Route::post('auth/{provider}/deAuthCallback', 'Auth\SocialAuthController@handleProviderDeAuthCallback');

Auth::routes();

Route::get('home', [
    'as'   => 'Home::index',
    'uses' => 'HomeController@index',
]);
