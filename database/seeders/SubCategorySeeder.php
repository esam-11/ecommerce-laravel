<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::where('name', 'إلكترونيات')->first();
        $clothing = Category::where('name', 'ملابس')->first();
        $home = Category::where('name', 'منزل ومطبخ')->first();
        $books = Category::where('name', 'كتب')->first();
        $sports = Category::where('name', 'رياضة')->first();
        $beauty = Category::where('name', 'جمال وصحة')->first();

        $subcategories = [
            // إلكترونيات
            [
                'name' => 'هواتف ذكية',
                'slug' => 'smartphones',
                'description' => 'هواتف ذكية واكسسوارات',
                'category_id' => $electronics->id,
                'is_active' => true,
            ],
            [
                'name' => 'لابتوب',
                'slug' => 'laptops',
                'description' => 'أجهزة لابتوب وحاسوب',
                'category_id' => $electronics->id,
                'is_active' => true,
            ],
            [
                'name' => 'تلفزيونات',
                'slug' => 'tvs',
                'description' => 'تلفزيونات وشاشات',
                'category_id' => $electronics->id,
                'is_active' => true,
            ],
            // ملابس
            [
                'name' => 'ملابس رجالية',
                'slug' => 'mens-clothing',
                'description' => 'ملابس رجالية متنوعة',
                'category_id' => $clothing->id,
                'is_active' => true,
            ],
            [
                'name' => 'ملابس نسائية',
                'slug' => 'womens-clothing',
                'description' => 'ملابس نسائية متنوعة',
                'category_id' => $clothing->id,
                'is_active' => true,
            ],
            [
                'name' => 'أحذية',
                'slug' => 'shoes',
                'description' => 'أحذية رجالية ونسائية',
                'category_id' => $clothing->id,
                'is_active' => true,
            ],
            // منزل ومطبخ
            [
                'name' => 'أدوات مطبخ',
                'slug' => 'kitchen-tools',
                'description' => 'أدوات وأواني مطبخ',
                'category_id' => $home->id,
                'is_active' => true,
            ],
            [
                'name' => 'ديكور منزلي',
                'slug' => 'home-decor',
                'description' => 'ديكورات وأثاث منزلي',
                'category_id' => $home->id,
                'is_active' => true,
            ],
            // كتب
            [
                'name' => 'كتب تعليمية',
                'slug' => 'educational-books',
                'description' => 'كتب تعليمية ومدرسية',
                'category_id' => $books->id,
                'is_active' => true,
            ],
            [
                'name' => 'روايات',
                'slug' => 'novels',
                'description' => 'روايات وأدب',
                'category_id' => $books->id,
                'is_active' => true,
            ],
            // رياضة
            [
                'name' => 'معدات جيم',
                'slug' => 'gym-equipment',
                'description' => 'معدات صالة رياضية',
                'category_id' => $sports->id,
                'is_active' => true,
            ],
            [
                'name' => 'ملابس رياضية',
                'slug' => 'sportswear',
                'description' => 'ملابس رياضية متنوعة',
                'category_id' => $sports->id,
                'is_active' => true,
            ],
            // جمال وصحة
            [
                'name' => 'مستحضرات تجميل',
                'slug' => 'cosmetics',
                'description' => 'مستحضرات تجميل نسائية',
                'category_id' => $beauty->id,
                'is_active' => true,
            ],
            [
                'name' => 'عناية بالبشرة',
                'slug' => 'skincare',
                'description' => 'منتجات عناية بالبشرة',
                'category_id' => $beauty->id,
                'is_active' => true,
            ],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create($subcategory);
        }
    }
}
