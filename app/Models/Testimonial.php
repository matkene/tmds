<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * Get the user that owns the testimonial.
     */

    public function users(){

    return $this->belongsTo(User::class);

    }
}
