<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FoodResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request):array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'description' => $this['description'],
            'price' => $this['price'],
            'image' => $this['image'],
            'is_chef_recommendation' => $this['chef_recommendation'],
            'allergens' => $this['allergens']->pluck('name'),
        ];
    }
}
