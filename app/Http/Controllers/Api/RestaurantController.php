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
         * This method retrieves a list of restaurants based on optional search parameters
         * and user location for distance filtering. It supports pagination through limit
         * and offset parameters.
         *
         * @param Request $request The incoming request containing query parameters.
         *
         * @queryParam int $limit (optional) The number of restaurants to return. Default is 10.
         * @queryParam int $offset (optional) The offset for pagination. Default is 0.
         * @queryParam string $search (optional) A search term to filter restaurants by name, address, website, or contact number. Default is an empty string.
         * @queryParam float $lat (optional) The user's latitude for distance calculation. Required for distance filtering.
         * @queryParam float $lng (optional) The user's longitude for distance calculation. Required for distance filtering.
         * @queryParam float $distance (optional) The maximum distance (in kilometers) to search for restaurants. Default is 10.
         *
         * @return JsonResource A collection of restaurants matching the search criteria.
         */
        public function index(Request $request): JsonResource
        {
            $limit = (int) $request->query('limit', 10);
            $offset = (int) $request->query('offset', 0);
            $search = trim($request->query('search', ''));

            // Felhasználói koordináták és távolság lekérdezése
            $userLat = $request->query('lat');
            $userLng = $request->query('lng');
            $maxDistance = (float) $request->query('distance', 10);

            $restaurantsQuery = Restaurant::with(['foods.allergens'])
                ->select(['id', 'name', 'contact_number', 'address', 'website', 'description', 'profile_image', 'lat', 'lng']);

            // Szűrés a keresési feltételek alapján
            if ($search) {
                $searchTerm = '%' . $search . '%';
                $restaurantsQuery->where(function ($query) use ($searchTerm) {
                    $query->where('contact_number', 'like', $searchTerm)
                        ->orWhere('address', 'like', $searchTerm)
                        ->orWhere('website', 'like', $searchTerm)
                        ->orWhere('name', 'like', $searchTerm);
                });
            }

            // Távolság szűrés
            if ($userLat && $userLng) {
                $restaurantsQuery->havingRaw("(6371 * acos(cos(radians(?)) * cos(radians(lat)) * cos(radians(lng) - radians(?)) + sin(radians(?)) * sin(radians(lat)))) <= ?", [$userLat, $userLng, $userLat, $maxDistance]);
            }

            $restaurants = $restaurantsQuery->offset($offset)->limit($limit)->get();

            return RestaurantResource::collection($restaurants);
        }
    }
