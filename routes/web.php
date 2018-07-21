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

Route::get('/hello', function() {
    return 'Hello World';
});
Route::post('/post-url', function() {
    return 'Post is a beautiful method';
});
Route::get('/get-url', function() {
    return 'Get is a beautiful method';
});
