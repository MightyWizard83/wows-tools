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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('auth/wargaming', 'AuthController@redirectToWargaming')->name('auth.wargaming');
Route::get('auth/wargaming/callback', 'AuthController@handleWargamingCallback')->name('auth.wargaming.handle');

Route::get('/hello', function() {
    return 'Hello World';
});
Route::post('/post-url', function() {
    return 'Post is a beautiful method';
});
Route::get('/get-url', function() {
    return 'Get is a beautiful method';
});


/**
 * Display All Players
 */
Route::get('/players', function () {
    $players = App\Player::orderBy('created_at', 'asc')->get();

    return view('players', [
        'players' => $players
    ]);
});


Route::get('/player/{id}', function () {
    //
});
