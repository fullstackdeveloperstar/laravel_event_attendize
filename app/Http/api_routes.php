<?php

Route::group(['prefix' => 'api'], function() {

    Route::post('login', [
        'as'   => 'login',
        'uses' => 'API\UserLoginAPIController@login',
    ]);

    Route::post('signup', [
        'as'   => 'signup',
        'uses' => 'API\UserLoginAPIController@signup',
    ]);

    Route::get('token', [
        'as'   => 'token',
        'uses' => 'API\UserLoginAPIController@getToken',
    ]);
});

Route::group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function () {

    /*
     * ---------------
     * Organisers
     * ---------------
     */


    /*
     * ---------------
     * Events
     * ---------------
     */
    Route::resource('events', 'API\EventsApiController');


    /*
     * ---------------
     * Attendees
     * ---------------
     */
    Route::resource('attendees', 'API\AttendeesApiController');


    /*
     * ---------------
     * Orders
     * ---------------
     */

    /*
     * ---------------
     * Users
     * ---------------
     */

    /*
     * ---------------
     * Check-In / Check-Out
     * ---------------
     */


    Route::get('/', function () {
        return response()->json([
            'Hello' => Auth::guard('api')->user()->full_name . '!'
        ]);
    });



    // Route::get('token', [
    //     'as'   => 'token',
    //     'uses' => 'API\UserLoginAPIController@getToken',
    // ]);

    Route::get('users', [
        'as'   => 'users',
        'uses' => 'API\UserLoginAPIController@index',
    ]);

    Route::get('users/{id}', [
        'as'   => 'users',
        'uses' => 'API\UserLoginAPIController@show',
    ]);
});