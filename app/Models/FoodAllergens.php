<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodAllergens extends Model
{
    protected $fillable = ['food_id', 'allergen_id'];

}
