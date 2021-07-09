<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user(){

      return $this->belongsTo(User::class, 'created_by');

    }

    public function booking(){

        return $this->hasMany(Booking::class);

      }
}
