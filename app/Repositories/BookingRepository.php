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



}
