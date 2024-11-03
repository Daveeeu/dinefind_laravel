<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request):array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'profile_image' => $this->profile_image,
            'contact_number' => $this->contact_number,
            'rating' => $this->rating,
            'menu' => FoodResource::collection($this->foods),
            'opening_hours' => OpeningHourResource::collection($this->openingHours),
        ];
    }
}
