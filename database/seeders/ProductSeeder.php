<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
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

        $smartphones = SubCategory::where('name', 'هواتف ذكية')->first();
        $laptops = SubCategory::where('name', 'لابتوب')->first();
        $maleClothing = SubCategory::where('name', 'ملابس رجالية')->first();
        $femaleClothing = SubCategory::where('name', 'ملابس نسائية')->first();
        $kitchen = SubCategory::where('name', 'أدوات مطبخ')->first();
        $educationalBooks = SubCategory::where('name', 'كتب تعليمية')->first();
        $gymEquipment = SubCategory::where('name', 'معدات جيم')->first();
        $skincare = SubCategory::where('name', 'عناية بالبشرة')->first();

        $products = [
            // إلكترونيات - هواتف ذكية
            [
                'name' => 'آيفون 15 برو',
                'slug' => 'iphone-15-pro',
                'description' => 'أحدث هاتف آيفون مع كاميرا متطورة ومعالج A17 Pro',
                'price' => 4500.00,
                'image' => 'products/iphone15pro.jpg',
                'sku' => 'IPH15PRO001',
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'stock_quantity' => 25,
                'is_active' => true,
            ],
            [
                'name' => 'سامسونج جالاكسي S24',
                'slug' => 'samsung-galaxy-s24',
                'description' => 'هاتف ذكي متطور مع كاميرا 200 ميجابكسل',
                'price' => 3800.00,
                'image' => 'products/galaxy-s24.jpg',
                'sku' => 'SAMS24PRO001',
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'stock_quantity' => 30,
                'is_active' => true,
            ],
            [
                'name' => 'هواوي P60 Pro',
                'slug' => 'huawei-p60-pro',
                'description' => 'هاتف ذكي مع كاميرا Leica متطورة',
                'price' => 3200.00,
                'image' =>'products/huawei-p60.jpg',
                'sku' => 'HWAP60PRO001',
                'category_id' => $electronics->id,
                'subcategory_id' => $smartphones->id,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            // إلكترونيات - لابتوب
            [
                'name' => 'ماك بوك برو M3',
                'slug' => 'macbook-pro-m3',
                'description' => 'لابتوب احترافي مع معالج M3 Pro',
                'price' => 8500.00,
                'image' => 'products/macbook-pro-m3.jpg',
                'sku' => 'MBPM3001',
                'category_id' => $electronics->id,
                'subcategory_id' => $laptops->id,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            // ملابس - رجالية
            [
                'name' => 'قميص قطني رجالي',
                'slug' => 'mens-cotton-shirt',
                'description' => 'قميص قطني عالي الجودة بألوان متعددة',
                'price' => 150.00,
                'image' => 'products/mens-shirt.jpg',
                'sku' => 'MCS001',
                'category_id' => $clothing->id,
                'subcategory_id' => $maleClothing->id,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
            // ملابس - نسائية
            [
                'name' => 'فستان صيفي نسائي',
                'slug' => 'womens-summer-dress',
                'description' => 'فستان أنيق مناسب للصيف',
                'price' => 180.00,
                'image' => 'products/womens-dress.jpg',
                'sku' => 'WSD001',
                'category_id' => $clothing->id,
                'subcategory_id' => $femaleClothing->id,
                'stock_quantity' => 35,
                'is_active' => true,
            ],
            // منزل ومطبخ
            [
                'name' => 'طقم أواني طبخ',
                'slug' => 'cookware-set',
                'description' => 'طقم أواني طبخ ستانلس ستيل عالي الجودة',
                'price' => 450.00,
                'image' => 'products/cookware-set.jpg',
                'sku' => 'CWS001',
                'category_id' => $home->id,
                'subcategory_id' => $kitchen->id,
                'stock_quantity' => 20,
                'is_active' => true,
            ],
            // كتب
            [
                'name' => 'كتاب تعلم البرمجة',
                'slug' => 'programming-book',
                'description' => 'كتاب شامل لتعلم البرمجة من الصفر',
                'price' => 80.00,
                'image' => 'products/programming-book.jpg',
                'sku' => 'PB001',
                'category_id' => $books->id,
                'subcategory_id' => $educationalBooks->id,
                'stock_quantity' => 60,
                'is_active' => true,
            ],
            // رياضة
            [
                'name' => 'دمبلز حديدية',
                'slug' => 'dumbbells',
                'description' => 'طقم دمبلز حديدية قابلة للتعديل',
                'price' => 350.00,
                'image' => 'products/dumbbells.jpg',
                'sku' => 'DB001',
                'category_id' => $sports->id,
                'subcategory_id' => $gymEquipment->id,
                'stock_quantity' => 15,
                'is_active' => true,
            ],
            // جمال وصحة
            [
                'name' => 'كريم مرطب للوجه',
                'slug' => 'face-moisturizer',
                'description' => 'كريم مرطب طبيعي للعناية بالبشرة',
                'price' => 65.00,
                'image' => 'products/face-moisturizer.jpg',
                'sku' => 'FM001',
                'category_id' => $beauty->id,
                'subcategory_id' => $skincare->id,
                'stock_quantity' => 50,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
