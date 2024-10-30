<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $fillable = [
        'name', 'description', 'profile_image', 'contact_number', 'address', 'website'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function foods()
    {
        return $this->hasMany(Foods::class);
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }
}

