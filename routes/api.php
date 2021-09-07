<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\Api\User\UserController;
use App\Http\Controllers\V1\Api\Admin\TourController;
use App\Http\Controllers\V1\Api\Auth\LoginController;
use App\Http\Controllers\V1\Api\Admin\EventController;
use App\Http\Controllers\V1\Api\Guest\GuestController;
use App\Http\Controllers\V1\Api\Admin\BookingController;
use App\Http\Controllers\V1\Api\Auth\RegisterController;
use App\Http\Controllers\V1\Api\Admin\DashboardController;
use App\Http\Controllers\V1\Api\Admin\HighlightController;
use App\Http\Controllers\V1\Api\Auth\AccountSettingsController;
use App\Http\Controllers\V1\Api\Admin\TestimonialController;
use App\Http\Controllers\V1\Api\Admin\TravelGuideController;
use App\Http\Controllers\V1\Api\Auth\VerificationController;
use App\Http\Controllers\V1\Api\Admin\PeopleCultureController;
use App\Http\Controllers\v1\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\V1\Api\User\TourController as UserTourController;
use App\Http\Controllers\V1\Api\User\EventController as UserEventController;
use App\Http\Controllers\V1\Api\User\BookingController as UserBookingController;
use App\Http\Controllers\V1\Api\User\DashboardController as UserDashboardController;
use App\Http\Controllers\V1\Api\User\HighlightController as UserHighlightController;
use App\Http\Controllers\V1\Api\User\TestimonialController as UserTestimonialController;
use App\Http\Controllers\V1\Api\User\TravelGuideController as UserTravelGuideController;
use App\Http\Controllers\V1\Api\User\PeopleCultureController as UserPeopleCultureController;

Route::group(['prefix' => 'v1'], function ($router) {

    Route::get('/test', function () {
        return 'Hello test 1';
    });

    Route::get('/check-username/{username}', [UserController::class, 'checkUsername']);

    // API To verify Payment
    Route::get('/booking/verify/{paymentRequestId}', [UserBookingController::class, 'verifyBookingPayment']);

    // API for contact us
    Route::post('/contact-us', [GuestController::class, 'contactUs']);

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
        Route::get('/dashboard', [UserDashboardController::class, 'index']);

        // Event
        Route::group(["prefix" => "events"], function () {
            Route::get('/', [UserEventController::class, 'index']);
            Route::post('view-single-event', [UserEventController::class, 'showEvent']);
        });

        // Tour Route
        Route::group(["prefix" => "tours"], function () {

            Route::get('/', [UserTourController::class, 'index']);
            Route::post('view-single-tour', [UserTourController::class, 'showTour']);
            Route::get('tour-history', [UserTourController::class, 'tourHistory']);
            Route::get('top-attraction', [UserTourController::class, 'topAttraction']);
            Route::get('favourite', [UserTourController::class, 'tourFavourite']);
        });

        // People Culture Route
        Route::group(["prefix" => "people-cultures"], function () {
            Route::get('/', [UserPeopleCultureController::class, 'index']);
        });

        // Highlight Route
        Route::group(["prefix" => "highlights"], function () {
            Route::get('/', [UserHighlightController::class, 'index']);
            Route::post('view-single-highlight', [UserHighlightController::class, 'showHighlight']);
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

        // Travel Guide Route
        Route::group(["prefix" => "travelguides"], function () {
            Route::get('/', [UserTravelGuideController::class, 'index']);
            Route::post('view-single-travelguide', [UserTravelGuideController::class, 'showTravelGuide']);
        });
    });

    // Admin Route
    Route::group(["prefix" => "admin",  "middleware" => ["auth:api", "admin"], "namespace" => "V1\Api\Admin"], function () {

        // Dashboard
        Route::group(["prefix" => "dashboard"], function () {
            Route::get('/', [DashboardController::class, 'index']);
        });

        // Events
        Route::group(["prefix" => "events"], function () {
            Route::get('/', [EventController::class, 'index']);
            Route::post('view-single-event', [EventController::class, 'showEvent']);
            Route::post('/create', [EventController::class, 'createEvent']);
            Route::post('/update', [EventController::class, 'update']);
            Route::get('/ongoing', [EventController::class, 'ongoingEvents']);
            Route::get('/upcoming', [EventController::class, 'upcomingEvents']);
            Route::get('/past', [EventController::class, 'pastEvents']);
        });

        // Tours
        Route::group(["prefix" => "tours"], function () {
            Route::get('/', [TourController::class, 'index']);
            Route::post('view-single-tour', [TourController::class, 'showTour']);
            Route::post('/create', [TourController::class, 'createTour']);
            Route::post('/update', [TourController::class, 'update']);
            Route::post('/activate', [TourController::class, 'activateTour']);
            Route::post('/deactivate', [TourController::class, 'deactivateTour']);
            Route::post('/delete', [TourController::class, 'deleteTours']);
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
            Route::post('view-single-highlight', [HighlightController::class, 'showHighlight']);
            Route::post('/update', [HighlightController::class, 'update']);
            Route::post('/create', [HighlightController::class, 'createHighlight']);
        });

        // Booking Route
        Route::group(["prefix" => "bookings"], function () {
            Route::post('/', [BookingController::class, 'index']);
            Route::post('online-booking', [BookingController::class, 'listAllOnlineBooking']);
            Route::post('pending-booking', [BookingController::class, 'listAllPendingBooking']);
            Route::post('completed-booking', [BookingController::class, 'listAllCompletedBooking']);
            Route::post('view-single-booking', [BookingController::class, 'showBooking']);
            //Route::post('/create', [BookingController::class, 'createBooking']);
        });


        // Testimonial Route
        Route::group(["prefix" => "testimonials"], function () {
            Route::get('/', [TestimonialController::class, 'index']);
            Route::post('view-single-testimonial', [TestimonialController::class, 'showTestimonial']);
        });

        // Travel Guide Route
        Route::group(["prefix" => "travelguides"], function () {
            Route::get('/', [TravelGuideController::class, 'index']);
            Route::post('view-single-travelguide', [TravelGuideController::class, 'showTravelGuide']);
            Route::post('/create', [TravelGuideController::class, 'createTravelGuide']);
            Route::post('/update', [TravelGuideController::class, 'update']);
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
