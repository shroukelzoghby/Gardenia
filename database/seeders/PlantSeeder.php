<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Plant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plant::create(
            [
                'name'=>'Snake Plant',
                'image'=>file_get_contents('public/plants/snake.jpg'),
                'type'=>'Indoor',

            ]
        );

    }
}
