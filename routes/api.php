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
Route::prefix('auth')->namespace('Api')->middleware(['api'])->group(function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('forget_password', 'AuthController@forgot_password');
});

Route::prefix('auth')->namespace('Api')->middleware(['api', 'jwt.verify'])->group(function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::post('update_profile', 'AuthController@update_profile');
    Route::post('change_password', 'AuthController@change_password');
});

Route::namespace('Api')->middleware(['api', 'jwt.verify'])->group(function () {
    //schedule routes
    Route::get('schedule/search', 'ApiTicketsController@search');

    //tickets routes
    Route::get('tickets', 'ApiTicketsController@all_tickets');
    Route::post('tickets/book', 'ApiTicketsController@book_ticket');
    Route::post('tickets/add_transaction', 'ApiTicketsController@update_ticket');

    //home & notifications
    Route::get('home', 'ApiController@home');
    Route::get('notifications', 'ApiController@notifications');
    Route::post('notifications/mark_read', 'ApiController@mark_all_read');
    Route::post('notifications/delete', 'ApiController@delete_notifications');
});