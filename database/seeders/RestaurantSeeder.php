<?php

    namespace Database\Seeders;

    use App\Models\Allergen;
    use App\Models\Foods;
    use App\Models\Restaurant;
    use App\Models\FoodAllergens;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class RestaurantSeeder extends Seeder
    {
        public function run()
        {
            DB::transaction(function () {
                // Allergének listája
                $allergens = [
                    'Glutén', 'Tej', 'Tojás', 'Földimogyoró', 'Hal',
                    'Rákfélék', 'Diófélék', 'Szójabab', 'Zeller', 'Mustár'
                ];

                foreach ($allergens as $allergen) {
                    Allergen::create(['name' => $allergen]);
                }

                $allergens = Allergen::all()->pluck('id')->toArray();

                // Hozz létre 10 éttermet
                for ($i = 1; $i <= 10; $i++) {
                    // Generálj véletlenszerű lat és lng értékeket
                    $lat = mt_rand(46000000, 48500000) / 1000000;
                    $lng = mt_rand(16000000, 22500000) / 1000000;

                    $restaurant = Restaurant::create([
                        'name' => "Étterem $i",
                        'description' => "Ez az Étterem $i leírása.",
                        'address' => "Cím $i",
                        'profile_image' => "https://example.com/images/restaurant_profile.jpg",
                        // 'image_url' => "https://example.com/images/restaurant.jpg",
                        'contact_number' => "123-456-789$i",
                        'lat' => $lat,
                        'lng' => $lng,
                    ]);

                    // Minden étteremhez 50 étel
                    for ($j = 1; $j <= 50; $j++) {
                        $food = Foods::create([
                            'restaurant_id' => $restaurant->id,
                            'name' => "Étel $j - Étterem $i",
                            'description' => "Ez az Étel $j leírása az Étterem $i számára.",
                            'price' => rand(1000, 5000),
                            'image' => 'https://example.com/images/food.jpg',
                            'chef_recommendation' => rand(0, 1)
                        ]);

                        $assignedAllergens = collect($allergens)->random(rand(1, 3));

                        foreach ($assignedAllergens as $allergenId) {
                            DB::table('food_allergen')->insert([
                                'food_id' => $food->id,
                                'allergen_id' => $allergenId,
                            ]);
                        }
                    }
                }
            });

            echo "Étteremek, ételek és allergén információk sikeresen feltöltve!\n";
        }
    }
