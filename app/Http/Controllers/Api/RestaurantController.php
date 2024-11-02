<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RestaurantResource;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the restaurants.
     *
     * Example URL: /api/restaurants?limit=10&offset=0&search=term
     *
     * @param  Request  $request
     * @return JsonResource
     */
    public function index(Request $request): JsonResource
    {
        $limit = (int) $request->query('limit', 10);
        $offset = (int) $request->query('offset', 0);
        $search = trim($request->query('search', ''));

        $restaurants = Restaurant::with(['foods.allergens'])
            ->select(['id', 'name', 'contact_number', 'address', 'website', 'description', 'profile_image'])
            ->when($search, function ($query) use ($search) {
                $searchTerm = '%' . $search . '%';
                return $query->where(function ($q) use ($searchTerm) {
                    $q->where('contact_number', 'like', $searchTerm)
                        ->orWhere('address', 'like', $searchTerm)
                        ->orWhere('website', 'like', $searchTerm)
                        ->orWhere('name', 'like', $searchTerm);
                });
            })
            ->offset($offset)
            ->limit($limit)
            ->get();

        return RestaurantResource::collection($restaurants);
    }


}
