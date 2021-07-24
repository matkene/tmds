<?php


namespace App\Http\Controllers\V1\Api\Admin;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Responser\JsonResponser;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    //
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

     /**
     * Get All Bookings details
     */
     public function index()
    {
        try {

            $bookingInstance = $this->bookingRepository->allBookings();

            if(!$bookingInstance){
                return JsonResponser::send(true, "Booking Record not found", null, 401);
            }

            return JsonResponser::send(false, "Booking Record found successfully.", $bookingInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }


    /**
     * Get Booking details by Id
     */
     public function showBooking(Request $request)
    {
        try {

            if(!isset($request->booking_id)){
                return JsonResponser::send(true, "Error occured. Please select a booking", null, 403);
            }

            $bookingInstance = $this->bookingRepository->findBookingById($request->booking_id);

            if(!$bookingInstance){
                return JsonResponser::send(true, "Booking Record not found", null, 401);
            }


            return JsonResponser::send(false, "Booking Record found successfully.", $bookingInstance);

        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }

    }



}
