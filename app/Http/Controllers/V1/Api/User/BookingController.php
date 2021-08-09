<?php


namespace App\Http\Controllers\V1\Api\User;

use App\Helpers\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Responser\JsonResponser;
use App\Repositories\BookingRepository;
use App\Interfaces\BookingTypeInterface;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CreateBookingRequest;
use App\Models\Tour;

class BookingController extends Controller
{
    //
    protected $bookingRepository;
    private $paymentToken;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->paymentToken = Payment::authenticate()->data->api_key;
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

            $userId = auth()->user()->id;

            DB::beginTransaction();

            //get tour
            $tourInstance = Tour::find($request->tour_id);
            if (is_null($tourInstance)) {
                return JsonResponser::send(true, "Unable to fetch data. Please refresh and try again", $tourInstance);
            }

            $newBookingInstance = $this->bookingRepository->create([
                "no_adults" => $request->no_adults,
                "no_children" => $request->no_children,
                "no_infants" => $request->no_infants,
                "date_of_visit" => $request->date_of_visit,
                "ticket_no" => $request->ticket_no,
                "user_id" => $userId,
                "tour_id" => $request->tour_id,
                "booking_type" => BookingTypeInterface::ONLINE_BOOKING,
                "amount" => $tourInstance->price, //to be modified
                "is_active" => true

            ]);
            if (!$newBookingInstance) {
                $error = true;
                $message = "Booking was not created successfully. Please try again";
                $data = [];
                return JsonResponser::send($error, $message, $data);
            }
            /*
            $data = [
                'email' => $userEmail,
                'name' => $userLastName.' '.$userFirstName,
                'user' => $newBookingInstance,
                'subject' => "Booking Created Successfully"
            ];
            Mail::to($userEmail)->send(new UserVerifyEmail($data));
            */
            DB::commit();
            $error = false;
            $message = "Your booking was successfully. Please check your email for ticket Id.";
            $data = $newBookingInstance;
            return JsonResponser::send($error, $message, $data);
        } catch (\Throwable $th) {
            DB::rollback();
            $error = true;
            $message = $th->getMessage();
            $data = [];
            return JsonResponser::send($error, $message, $data);
        }
    }
}
