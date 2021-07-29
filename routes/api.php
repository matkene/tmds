<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\User\UserController;
use App\Http\Controllers\V1\Api\Admin\TourController;
use App\Http\Controllers\V1\Api\Admin\PeopleCultureController;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Admin\TestimonialController;
use App\Http\Controllers\V1\Api\Admin\TravelGuideController;
use App\Http\Controllers\V1\Api\Admin\HighlightController;
use App\Http\Controllers\V1\Api\Admin\EventController;
use App\Http\Controllers\V1\Api\Guest\GuestController;
use App\Http\Controllers\V1\Api\Auth\RegisterController;
use App\Http\Controllers\V1\Auth\AccountSettingsController;
use App\Http\Controllers\V1\Api\Auth\VerificationController;
use App\Http\Controllers\v1\Api\Auth\ForgotPasswordController;

use App\Http\Controllers\V1\Api\User\EventController as UserEventController;
use App\Http\Controllers\V1\Api\User\TourController as UserTourController;
use App\Http\Controllers\V1\Api\User\PeopleCultureController as UserPeopleCultureController;
use App\Http\Controllers\V1\Api\User\TestimonialController as UserTestimonialController;
use App\Http\Controllers\V1\Api\User\TravelGuideController as UserTravelGuideController;
use App\Http\Controllers\V1\Api\User\HighlightController as UserHighlightController;

Route::group(['prefix' => 'v1'], function ($router) {

    Route::get('/test', function () {
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
            Route::get('/show/{id}', [TourController::class, 'showTour']);
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

    // Guest Route
    Route::group(["prefix" => "guest", "namespace" => "V1\Api\Guest"], function () {

        // Events
        Route::group(["prefix" => "events"], function () {
            Route::get('/', [GuestController::class, 'listAllActiveEvents']);
            Route::post('/', [GuestController::class, 'viewSingleEvent']);
        });

        // Tour
        Route::group(["prefix" => "tours"], function () {
            Route::get('/', [GuestController::class, 'listAllActiveTours']);
            Route::post('/', [GuestController::class, 'viewSingleTour']);
        });

        // People Culture
        Route::group(["prefix" => "peoplecultures"], function () {
            Route::get('/', [GuestController::class, 'listAllActivePeopleCultures']);
        });

        // People Highlight
        Route::group(["prefix" => "highlights"], function () {
            Route::get('/', [GuestController::class, 'listAllActiveHighlights']);
        });

        // People Testimonial
        Route::group(["prefix" => "testimonials"], function () {
            Route::get('/', [GuestController::class, 'listAllActiveTestimonials']);
        });

        // People Travel Guide
        Route::group(["prefix" => "travelguides"], function () {
            Route::get('/', [GuestController::class, 'listAllActiveTravelGuides']);
        });
    });
});
