<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'description', 'profile_image', 'contact_number', 'address', 'website', 'lat', 'lng'
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function foods(): HasMany
    {
        return $this->hasMany(Foods::class);
    }

    /**
     * Get the opening hours for the restaurant.
     */
    public function openingHours()
    {
        return $this->hasMany(OpeningHour::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

}

