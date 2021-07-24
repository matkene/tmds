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
use App\Http\Controllers\v1\Api\User\TourController AS UserTourController;
use App\Http\Controllers\v1\Api\Admin\PeopleCultureController;
use App\Http\Controllers\v1\Api\User\PeopleCultureController AS UserPeopleCultureController;
use App\Http\Controllers\v1\Api\Admin\HighlightController;
use App\Http\Controllers\v1\Api\User\HighlightController AS UserHighlightController;
use App\Http\Controllers\v1\Api\Admin\BookingController;
use App\Http\Controllers\v1\Api\User\BookingController AS UserBookingController;
use App\Http\Controllers\v1\Api\Admin\TestimonialController;
use App\Http\Controllers\v1\Api\User\TestimonialController AS UserTestimonialController;


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
            Route::get('/', [UserTourController::class, 'index']);
            Route::post('view-single-tour', [UserTourController::class, 'showTour']);
        });

        // People Culture Route
        Route::group(["prefix" => "people-cultures"], function () {
            Route::get('/', [UserPeopleCultureController::class, 'index']);
        });

        // Highlight Route
        Route::group(["prefix" => "highlights"], function () {
            Route::get('/', [UserHighlightController::class, 'index']);
        });

        // Booking Route
        Route::group(["prefix" => "bookings"], function () {
            Route::post('/create', [UserBookingController::class, 'createBooking']);
            Route::post('view-single-booking', [UserBookingController::class, 'showBooking']);
        });

        // Testimonial Route
        Route::group(["prefix" => "testimonials"], function () {
            Route::get('/', [UserTestimonialController::class, 'index']);
            Route::post('/create', [UserTestimonialController::class, 'createTestimonial']);
            Route::post('view-single-testimonial', [UserTestimonialController::class, 'showTestimonial']);
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


        // People Culture Route
        Route::group(["prefix" => "people-cultures"], function () {
            Route::get('/', [PeopleCultureController::class, 'index']);
            Route::post('view-single-people-culture', [PeopleCultureController::class, 'showPeopleCulture']);
            Route::post('/update', [PeopleCultureController::class, 'update']);
        });

         // Highlight Route
        Route::group(["prefix" => "highlights"], function () {
            Route::get('/', [HighlightController::class, 'index']);
            Route::post('view-single-highlight', [HighlightController::class, 'showHightlight']);
            Route::post('/update', [HighlightController::class, 'update']);
        });

        // Booking Route
        Route::group(["prefix" => "bookings"], function () {
            Route::get('/', [BookingController::class, 'index']);
            Route::post('view-single-booking', [BookingController::class, 'showBooking']);
            //Route::post('/create', [BookingController::class, 'createBooking']);
        });


        // Testimonial Route
        Route::group(["prefix" => "testimonials"], function () {
            Route::get('/', [TestimonialController::class, 'index']);
            Route::post('view-single-testimonial', [TestimonialController::class, 'showTestimonial']);
        });


    });

});
