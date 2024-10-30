<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foods extends Model
{
    protected $fillable = [
        'restaurant_id', 'name', 'image','description', 'price', 'image', 'chef_recommendation'
    ];
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function allergens()
    {
        return $this->belongsToMany(Allergen::class, 'food_allergen', 'food_id', 'allergen_id');
    }


    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'menu_food');
    }
}

