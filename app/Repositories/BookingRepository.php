<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Booking;


class BookingRepository {

    private $modelInstance;

    public function __construct(Booking $booking) {
        $this->modelInstance = $booking;
    }

    public function allBookings()
    {

        return $this->modelInstance::with('tour','user')
        ->orderBy('id', 'DESC')
        ->paginate(3);

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
