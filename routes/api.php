<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\User\UserController;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Auth\RegisterController;
use App\Http\Controllers\v1\Auth\AccountSettingsController;
use App\Http\Controllers\V1\Api\Auth\VerificationController;
use App\Http\Controllers\v1\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\v1\Api\Admin\EventController;
use App\Http\Controllers\v1\Api\User\EventController AS UserEventController;
use App\Http\Controllers\v1\Api\Admin\TourController;

Route::group(['prefix' => 'v1'], function ($router) {

    Route::get('/test', function() {
        return 'Hello test 1';
    });

    // authentication
    Route::group(['prefix' => 'auth', "namespace" => "V1\Api\Auth"], function () {

        Route::post('signup', [RegisterController::class, 'createUser']);
        Route::post('admin/signup', [RegisterController::class, 'createAdmin']);
        Route::get('/email/verification/{code}', [VerificationController::class, 'verifyUser']);
        Route::post('email/resend-verification', [RegisterController::class, 'resendCode']);
        Route::post('login', [LoginController::class, 'login']);
        Route::get('logout', [LoginController::class, 'logout'])->middleware("auth:api");
        Route::post('recover', [ForgotPasswordController::class, 'recover']);
        Route::post('reset/password', [ForgotPasswordController::class, 'reset']);
        // update password
        Route::post('update/password', [AccountSettingsController::class, 'updatePassword'])->middleware("auth:api");
    });

    // User Route
    Route::group(["prefix" => "user",  "middleware" => ["auth:api", "user"], "namespace" => "V1\Api\User"], function () {

        Route::get('/', [UserController::class, 'index']);
        Route::post('update/', [UserController::class, 'update']);

        // Event
        Route::group(["prefix" => "events"], function () {
            Route::get('/', [UserEventController::class, 'index']);
            Route::post('view-single-event', [UserEventController::class, 'showEvent']);
        });

        // Tour Route
        Route::group(["prefix" => "tours"], function () {
            Route::get('/', [TourController::class, 'index']);
            Route::get('/show/{id}',[TourController::class, 'showTour']);
        });

    });

    // Admin Route
    Route::group(["prefix" => "admin",  "middleware" => ["auth:api", "admin"], "namespace" => "V1\Api\Admin"], function () {

          // Events
        Route::group(["prefix" => "events"], function () {
            Route::get('/', [EventController::class, 'index']);
            Route::post('view-single-event', [EventController::class, 'showEvent']);
            Route::post('/create', [EventController::class, 'createEvent']);
            Route::post('/update', [EventController::class, 'update']);
        });

        // Tours
        Route::group(["prefix" => "tours"], function () {
            Route::get('/', [TourController::class, 'index']);
            Route::post('view-single-tour', [TourController::class, 'showTour']);
            Route::post('/create', [TourController::class, 'createTour']);
            Route::post('/update', [TourController::class, 'update']);
        });

    });

});
