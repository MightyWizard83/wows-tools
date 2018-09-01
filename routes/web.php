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

Route::get('/update_ratings', function () {

    $coefficients = file_get_contents("https://api.eu.warships.today/json/wows/ratings/warships-today-rating/coefficients");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/coefficients.json'), $coefficients);
    
    $ratings = file_get_contents("https://wows-numbers.com/personal/rating/expected/json/");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/ratings-expected.json'), $ratings);
});


Route::get('sync_player/{id}', 'WgAPIController@syncPlayer');

Route::get('sync_player_test/{id}', 'WgAPIController@syncPlayerTest');

Route::get('/player/{id}', function () {
    //
});
