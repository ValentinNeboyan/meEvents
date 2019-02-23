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

Route::get('/get_links', 'LinkController@getLink')->name('parser.get_links');
Route::get('/get_info', 'EventController@getEventInfo')->name('parser.get_event_info');
Route::get('/events', 'EventController@showEvents')->name('parser.show_events');
Route::get('/event{event}/', 'EventController@showEvent')->name('parser.show_event');
