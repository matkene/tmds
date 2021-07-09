<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission; // Added too

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    use HasRoleAndPermission; // Added as request in Laravel-roles installation

    // Values for user roles
    const isUser = 1;
    const isDeveloper = 3;
    const isAdmin = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'password', 'phoneno', 'state', 'country', 'is_verified', 'can_login', 'is_active', 'username', 'gender', 'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    /*
     * Get the booking for the user.
     */

    public function bookings(){

       return $this->hasMany(Booking::class);
    }
    /*
     * Get the testimonial for the user.
     */

    public function testimonials(){

       return $this->hasMany(Testimonial::class);
    }

    public function booking(){

        return $this->hasMany(Booking::class);
     }

    public function tours(){

       return $this->hasMany(Tour::class, 'created_by');
    }

    public function events(){

       return $this->hasMany(Event::class, 'created_by');
    }

    public function highlights(){

       return $this->hasMany(Highlight::class, 'created_by');
    }

    public function peopleCultures(){

       return $this->hasMany(PeopleCulture::class, 'created_by');
    }

    public function travelGuides(){

       return $this->hasMany(TravelGuide::class, 'created_by');
    }
}
