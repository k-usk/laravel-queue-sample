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

Route::get('/users', 'UsersController@users');
Route::post('/users', 'UsersController@add_queue');

//HTTPS
Route::group(array('https'), function(){
    Route::post('/users', array('as' => 'form/action', 'uses' => 'UsersController@add_queue'));
});