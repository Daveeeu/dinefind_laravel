<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialOpeningHour extends Model
{
    protected $fillable = [
        'restaurant_id', 'date', 'open_time', 'close_time', 'is_closed'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
