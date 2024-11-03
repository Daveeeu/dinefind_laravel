<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpeningHour extends Model
{
    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'open_time',
        'close_time',
    ];

    /**
     * Get the restaurant that owns the opening hours.
     */
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
