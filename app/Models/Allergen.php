<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergen extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public function foods()
    {
        return $this->belongsToMany(Foods::class, 'food_allergen');
    }
}

