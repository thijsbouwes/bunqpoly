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


Auth::routes();

Route::get('/', 'HomeController@show');

Route::get('/bunqpoly/login', function () {
    return redirect('/');
});

Route::get('/game', function () {
    return view('dice');
});

Route::get('/test', function() {
    dd(config('bunqpoly.cards'));
});