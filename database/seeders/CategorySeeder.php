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
        $categories = [
            [
                'name' => 'إلكترونيات',
                'slug' => 'electronics',
                'description' => 'أجهزة إلكترونية متنوعة',
                'image' => 'electronics.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'ملابس',
                'slug' => 'clothing',
                'description' => 'ملابس رجالية ونسائية',
                'image' => 'clothing.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'منزل ومطبخ',
                'slug' => 'home-kitchen',
                'description' => 'أدوات منزلية ومطبخ',
                'image' => 'home.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'كتب',
                'slug' => 'books',
                'description' => 'كتب ومجلات',
                'image' => 'books.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'رياضة',
                'slug' => 'sports',
                'description' => 'معدات رياضية',
                'image' => 'sports.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'جمال وصحة',
                'slug' => 'beauty-health',
                'description' => 'منتجات جمالية وصحية',
                'image' => 'beauty.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
