<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'no_adults', 'no_children', 'no_infants', 'is_active', 'is_attended', 'payment_status', 'date_of_visit', 'ticket_no', 'user_id', 'tour_id'
    ];

    /**
     * Get the user that owns the booking.
     */

    public function user(){

        return $this->belongsTo(User::class, 'user_id');

    }

    public function tour(){

        return $this->belongsTo(Tour::class, 'tour_id');

    }
}
