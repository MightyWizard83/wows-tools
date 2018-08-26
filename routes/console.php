<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('update_ratings', function () {

    $coefficients = file_get_contents("https://api.eu.warships.today/json/wows/ratings/warships-today-rating/coefficients");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/coefficients.json'), $coefficients);
    
    $ratings = file_get_contents("https://wows-numbers.com/personal/rating/expected/json/");
    //check if error && 200 3xx
    file_put_contents(public_path('../storage/app/public/ratings-expected.json'), $ratings);
});