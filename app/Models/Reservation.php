<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'restaurant_id', 'reservation_date', 'reservation_time', 'num_guests',
        'special_requests', 'guest_name', 'guest_contact', 'attendance_confirmed'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}

