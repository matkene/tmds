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
     protected $guarded = [
        'id'
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
