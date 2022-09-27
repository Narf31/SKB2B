<?php

$domain = env('CLIENT_DOMAIN');//client.localhost


Route::group(['domain' => $domain], function () {


    Route::group(['prefix' => '', 'namespace' => 'Client'], function () {


        Route::get('/', 'HomeClientController@index');
        Route::get('/profile', 'HomeClientController@profile');
        Route::get('/profile/info', 'HomeClientController@showContent');



        Route::get('/login', 'Auth\LoginController@showLoginForm');
        Route::post('/login', 'Auth\LoginController@login');
        Route::get('/logout', 'Auth\LoginController@logout');
        Route::post('/change-pass', 'Auth\LoginController@change_pass');


        Route::get('/registration', 'Auth\LoginController@showRegistrationForm');
        Route::post('/registration', 'Auth\LoginController@registration');


        Route::group(['prefix' => 'contracts', 'namespace' => 'Сontracts'], function () {

            Route::get('/', 'СontractsClientController@index');
            Route::get('/list', 'СontractsClientController@contracts');


            Route::group(['prefix' => 'online', 'namespace' => 'Online'], function () {


                Route::get('/{product_id}/create', 'OnlineClientController@create');
                Route::get('/view/{md5_token}/', 'OnlineClientController@edit');
                Route::post('/save/{md5_token}', 'OnlineClientController@save');
                Route::get('/calc/{md5_token}', 'OnlineClientController@calc');
                Route::post('/release/{md5_token}', 'OnlineClientController@release');


                Route::get('/action/{md5_token}/subject', 'ActionClientController@subject');

                Route::get('/payment-info/{md5_token}', 'OnlineClientController@payment_info');
                Route::get('/payment-link/{md5_token}', 'OnlineClientController@payment_link');


            });

        });

        Route::group(['prefix' => 'damages', 'namespace' => 'Damages'], function () {

            Route::get('/', 'DamagesClientController@index');
            Route::get('/create', 'DamagesClientController@create');
            Route::post('/create', 'DamagesClientController@save');
            Route::get('/product/{id}', 'DamagesClientController@product');
            Route::post('/actions/get-point-sale', 'ActionsController@get_point_sale');
            Route::post('/actions/scan/{order_id}', 'ActionsController@scan_damages');


            Route::group(['prefix' => 'order/{id}', 'namespace' => 'Orders'], function () {
                Route::get('/', 'OrdersClientController@index');

            });


        });


        Route::group(['prefix' => 'suggestions/dadata', 'namespace' => 'DaData'], function () {

            Route::get('detectAddressByIp', 'DaDataController@detectAddressByIp');

            Route::get('status/address', 'DaDataController@status_address');
            Route::get('status/fio', 'DaDataController@status_fio');
            Route::get('status/party', 'DaDataController@status_party');

            Route::post('suggest/address', 'DaDataController@address');
            Route::post('suggest/fio', 'DaDataController@fio');
            Route::post('suggest/party', 'DaDataController@party');

        });

    });

});




