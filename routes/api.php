<?php


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
/*
Route::group(['prefix' => '', 'namespace' => 'MobileAPI', 'middleware' => 'mobile_logger'], function () {

    //Route::resource('mobile', 'MobileAPIController');
    //Route::post('mobile', 'MobileAPIController@index');

});

Route::post('/user/login', 'API\AuthAPIController@Login')->name('login-api');
Route::get('/policy/get-policies-1c', 'API\APIController@GetPolices1C')->name('get-policies-1c');

Route::group(['prefix' => '/load', 'namespace' => 'VermaAPI'], function () {

    //Route::resource('mobile', 'MobileAPIController');
    Route::get('set-data', 'VermaAPIController@data');
    Route::post('set-data', 'VermaAPIController@data');

});
*/


Route::group(['prefix' => '/tit-rest', 'namespace' => 'API\TitRest'], function () {

    Route::get('payments', 'ApiRestController@getPayments');
    Route::get('contract', 'ApiRestController@getContract');

});
