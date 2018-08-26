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

Route::get('/update_ratings', function () {

    $coefficients = file_get_contents("https://api.eu.warships.today/json/wows/ratings/warships-today-rating/coefficients");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/coefficients.json'), $coefficients);
    
    $ratings = file_get_contents("https://wows-numbers.com/personal/rating/expected/json/");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/ratings-expected.json'), $ratings);
});