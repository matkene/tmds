<?php

namespace App\Repositories;

use App\Helpers\Payment;
use App\Http\Responser\JsonResponser;
use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Interfaces\BookingTypeInterface;
use App\Models\Tour;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BookingRepository
{

    private $modelInstance;
    private $paymentToken;

    public function __construct(Booking $booking)
    {
        $this->modelInstance = $booking;
        $this->paymentToken = Payment::authenticate()->data->api_key;
    }

    public function allBookings($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour', 'user')
            ->when($searchParam, function ($query, $searchParam) use ($request) {
                return $query->where('ticket_no', $searchParam);
            })
            ->when($dateSearchParam, function ($query, $dateSearchParam) use ($request) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }

    public function listOnlineBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour', 'user')
            ->when($searchParam, function ($query, $searchParam) use ($request) {
                return $query->where('ticket_no', $searchParam);
            })
            ->when($dateSearchParam, function ($query, $dateSearchParam) use ($request) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->where('booking_type', BookingTypeInterface::ONLINE_BOOKING)
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }

    public function listPendingBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour', 'user')
            ->when($searchParam, function ($query, $searchParam) use ($request) {
                return $query->where('ticket_no', $searchParam);
            })
            ->when($dateSearchParam, function ($query, $dateSearchParam) use ($request) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->where('status', 'pending')
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }

    public function listCompletedBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour', 'user')
            ->when($searchParam, function ($query, $searchParam) use ($request) {
                return $query->where('ticket_no', $searchParam);
            })
            ->when($dateSearchParam, function ($query, $dateSearchParam) use ($request) {
                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                return $query->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->where('status', 'completed')
            ->where('is_attended', true)
            ->orderBy('id', 'DESC')
            ->paginate(5);
    }

    public function findBookingById($id)
    {

        return $this->modelInstance::with('tour', 'user')->where('id', $id)->first();
    }

    public function create($dataToCreate)
    {

        return $this->modelInstance::firstOrCreate($dataToCreate);
    }

    public function processTotalRevenue()
    {
        return $this->modelInstance::sum('amount');
    }

    public function processTodayRevenue()
    {
        return $this->modelInstance::whereDate('created_at', Carbon::today())->where('payment_status', 'Completed')->sum('amount');
    }

    public function processRevenueByPaymentMethod($paymentMethod)
    {
        return $this->modelInstance::where('payment_method', $paymentMethod)->where('payment_status', 'Completed')->sum('amount');
    }

    public function processTotalVisit()
    {
        return $this->modelInstance::where("is_attended", true)->where("status", "completed")->count();
    }

    public function processAdultVisit()
    {
        return $this->modelInstance::where("is_attended", true)->where("status", "completed")->sum('no_adults');
    }

    public function processChildrenVisit()
    {
        return $this->modelInstance::where("is_attended", true)->where("status", "completed")->sum('no_children');
    }

    public function processInfantVisit()
    {
        return $this->modelInstance::where("is_attended", true)->where("status", "completed")->sum('no_infants');
    }

    public function processVisitType($bookingType)
    {
        return $this->modelInstance::where("booking_type", $bookingType)->count();
    }

    public function processBooking($request)
    {
        $user = Auth::user();
        $userId = auth()->user()->id;


        //get tour
        $tourInstance = Tour::find($request['tour_id']);
        if (is_null($tourInstance)) {
            return [
                'error' => true,
                'message' => 'Tour Id Not Found',
                'data' => [],
            ];
        }


        // Save the booking to the db
        $booking = Booking::create([
            "no_adults" => $request['no_adults'],
            "no_children" => $request['no_children'],
            "no_infants" => $request['no_infants'],
            "date_of_visit" => $request['date_of_visit'],
            "ticket_no" => $request['ticket_no'],
            "user_id" => $userId,
            "tour_id" => $request['tour_id'],
            "booking_type" => BookingTypeInterface::ONLINE_BOOKING,
            "amount" => $tourInstance->price, //to be modified
            "is_active" => true
        ]);


        //Make the payment
        $client = new Client();
        $url = config('payment.base_url') . '/mda-integration/generate-bill';


        $response = $client->post($url, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->paymentToken,
            ],
            'json' => [
                "customer_first_name" => $user->firstname,
                "customer_last_name" => $user->lastname,
                "customer_email" => $user->email,
                "customer_phone" => $user->phoneno,
                "customer_address" => $user->state . ',  ' . $user->country,
                "bill_description" => 'Payment for one round tour for ' . $user->firstname . ' ' . $user->lastname, //$tourInstance->description,
                "billed_amount" => floatval($tourInstance->price),
                "overwrite_existing" => false,
                "request_id" => time(),
                "service_id" =>  156,
                "demand_notices" => array(
                    array(
                        "name" => "Earnings from Olumo tourists' centre",
                        "amount" => floatval($tourInstance->price),
                        "revenue_code" => "200040021114005"
                    )
                )
            ]
        ]);

        $data =  json_decode($response->getBody());

        // Get the payment Id
        $paymentRequestId = $data->data->request_id;

        // Update the database to hold Payment Request Id
        $tourBooking = $this->modelInstance::whereId($booking['id'])->first();
        $tourBooking->payment_request_id = $paymentRequestId;
        $tourBooking->save();

        return [
            'error' => false,
            'message' => 'Data retrieved',
            'data' => $data
        ];
    }

    public function verifyBookingPayment($paymentRequestId)
    {
        $client = new Client();
        $url = config('payment.base_url');

        $response = $client->get($url . '/mda-integration/get-bill?request_id=' . $paymentRequestId, [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->paymentToken,
            ],
        ]);

        $data = json_decode($response->getBody());

        if (count($data->data->payments_transactions) === 0) {
            return [
                'paid' => false,
            ];
        } else {

            $booking = $this->modelInstance::wherePaymentRequestId($paymentRequestId)->first();
            $booking->payment_status = 'Paid';
            $booking->save();

            return [
                'paid' => true,
                $data
            ];
        }
    }
}
