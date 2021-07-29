<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TravelGuide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'is_active', 'image', 'created_by'
    ];

    protected $guarded = ['id'];

    public function user(){

    return $this->belongsTo(User::class, 'created_by');

    }
}
