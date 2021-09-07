<?php


namespace App\Http\Controllers\V1\Api\User;

use App\Helpers\Payment;
use App\Helpers\ProcessAuditLog;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Responser\JsonResponser;
use App\Repositories\BookingRepository;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateBookingRequest;

class BookingController extends Controller
{
    //
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }


    /**
     * Get Booking details by Ids
     */
    public function showBooking(Request $request)
    {
        try {

            if (!isset($request->booking_id)) {
                return JsonResponser::send(true, "Error occured. Please select a booking", null, 403);
            }

            $bookingInstance = $this->bookingRepository->findBookingById($request->booking_id);

            if (!$bookingInstance) {
                return JsonResponser::send(true, "Booking Record not found", null, 401);
            }

            return JsonResponser::send(false, "Booking Record found successfully.", $bookingInstance);
        } catch (\Throwable $error) {
            return $error->getMessage();
            logger($error);
            return JsonResponser::send(true, 'Internal server error', null, 500);
        }
    }

    public function createBooking(CreateBookingRequest $request)
    {
        try {
            $data = $request->all();
            $createBooking = $this->bookingRepository->processBooking($data);

            if ($createBooking['error'] == true) {
                return JsonResponser::send(true, $createBooking['message'], $createBooking['data']);
            }

            return JsonResponser::send(false, $createBooking['message'], $createBooking['data']);
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }

    public function verifyBookingPayment($paymentRequestId)
    {
        try {

            $verifyBooking = $this->bookingRepository->verifyBookingPayment($paymentRequestId);


            return JsonResponser::send(false, 'Data Retrieved', $verifyBooking);
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }
}
