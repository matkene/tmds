<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleCulture extends Model
{
    use HasFactory;


    protected $fillable = [
        'image', 'created_by', 'key'
    ];


    protected $guarded = ['id'];


    public function user(){

        return $this->belongsTo(User::class, 'created_by');

    }

}
