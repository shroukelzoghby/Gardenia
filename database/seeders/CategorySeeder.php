<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(
            [
                'name'=>'Indoor'
            ]
        );
        Category::create(
            [
                'name'=>'Outdoor'
            ]
        );
        Category::create(
            [
                'name'=>'Garden'
            ]
        );
        Category::create(
            [
                'name'=>'Office'
            ]
        );
    }
}
