<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Auth\RegisterController;


Route::group(['prefix' => 'v1'], function ($router) {

    Route::get('/test', function() {
        return 'Hello test 1';
    });

    // authentication
    Route::group(['prefix' => 'auth', "namespace" => "V1\Api\Auth"], function () {

        Route::post('signup', [RegisterController::class, 'createUser']);
        // Route::get('test', 'RegisterController@mergeTwoLists');
        // Route::post('admin/signup', 'RegisterController@createAdmin');
        // Route::get('/email/verification/{code}', 'VerificationController@verifyUser');
        // Route::post('email/resend-verification', 'RegisterController@resendCode');
        Route::post('login', [LoginController::class, 'login']);
        // Route::get('logout', 'LoginController@logout')->middleware("auth:api");
        // Route::post('recover', 'ForgotPasswordController@recover');
        // Route::post('reset/password', 'ForgotPasswordController@reset');
        // // // update password
        // Route::post('update/password', 'AccountSettingsController@updatePassword')->middleware("auth:api");
        // // social signup
        // Route::post('/social/signup', 'SocialAuthController@socialAuth');
    });
});
