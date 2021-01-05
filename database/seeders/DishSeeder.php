<?php

namespace Database\Seeders;

use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dish::insert([
            [
                'dish' => 'Veg Biryani',
                'available' => 5,
                'price' => 70
            ],
            [
                'dish' => 'Chicken Biryani',
                'available' => 15,
                'price' => 100
            ],
            [
                'dish' => 'Meal',
                'available' => 5,
                'price' => 70
            ],
            [
                'dish' => 'Special Meal',
                'available' => 15,
                'price' => 100
            ],
            [
                'dish' => 'Tea',
                'available' => 100,
                'price' => 10
            ],
        ]);
    }
}