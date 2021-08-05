<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use App\Interfaces\BookingTypeInterface;
use Illuminate\Database\Eloquent\Builder;


class BookingRepository {

    private $modelInstance;

    public function __construct(Booking $booking) {
        $this->modelInstance = $booking;
    }

    public function allBookings($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour','user')
                                    ->when($searchParam, function($query, $searchParam) use($request) {
                                        return $query->where('ticket_no', $searchParam);
                                    })
                                    ->when($dateSearchParam, function($query, $dateSearchParam) use($request) {
                                        $startDate = Carbon::parse($request->start_date);
                                        $endDate = Carbon::parse($request->end_date);
                                        return $query->whereBetween(\DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                                    })
                                    ->orderBy('id', 'DESC')
                                    ->paginate(5);

    }

    public function listOnlineBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour','user')
                                    ->when($searchParam, function($query, $searchParam) use($request) {
                                        return $query->where('ticket_no', $searchParam);
                                    })
                                    ->when($dateSearchParam, function($query, $dateSearchParam) use($request) {
                                        $startDate = Carbon::parse($request->start_date);
                                        $endDate = Carbon::parse($request->end_date);
                                        return $query->whereBetween(\DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                                    })
                                    ->where('booking_type', BookingTypeInterface::ONLINE_BOOKING)
                                    ->orderBy('id', 'DESC')
                                    ->paginate(5);

    }

    public function listPendingBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour','user')
                                    ->when($searchParam, function($query, $searchParam) use($request) {
                                        return $query->where('ticket_no', $searchParam);
                                    })
                                    ->when($dateSearchParam, function($query, $dateSearchParam) use($request) {
                                        $startDate = Carbon::parse($request->start_date);
                                        $endDate = Carbon::parse($request->end_date);
                                        return $query->whereBetween(\DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                                    })
                                    ->where('status', 'pending')
                                    ->orderBy('id', 'DESC')
                                    ->paginate(5);

    }

    public function listCompletedBooking($request)
    {

        $searchParam = $request->search_params;
        (!is_null($request->start_date) && !is_null($request->end_date)) ? $dateSearchParam = true : $dateSearchParam = false;


        return $this->modelInstance::with('tour','user')
                                    ->when($searchParam, function($query, $searchParam) use($request) {
                                        return $query->where('ticket_no', $searchParam);
                                    })
                                    ->when($dateSearchParam, function($query, $dateSearchParam) use($request) {
                                        $startDate = Carbon::parse($request->start_date);
                                        $endDate = Carbon::parse($request->end_date);
                                        return $query->whereBetween(\DB::raw('DATE(created_at)'), [$startDate, $endDate]);
                                    })
                                    ->where('status', 'Completed')
                                    ->where('is_attended', true)
                                    ->orderBy('id', 'DESC')
                                    ->paginate(5);

    }

    public function findBookingById($id)    {

       return $this->modelInstance::with('tour', 'user')->where('id', $id)->first();

    }

    public function create($dataToCreate){

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



}
