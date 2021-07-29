<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Highlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'image','title', 'video','slug','description','created_by','is_active'
    ];

    protected $guarded = ['id'];

    public function user(){

    return $this->belongsTo(User::class, 'created_by');

    }
}
