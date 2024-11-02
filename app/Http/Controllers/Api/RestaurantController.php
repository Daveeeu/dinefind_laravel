<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantController extends Controller
{
    public function index(Request $request): JsonResource
    {
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $restaurants = Restaurant::with('foods.allergens')
            ->skip($offset)
            ->take($limit)
            ->get();

        return RestaurantResource::collection($restaurants);
    }
}
