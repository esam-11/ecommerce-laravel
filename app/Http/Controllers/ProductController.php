<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by subcategory
        if ($request->filled('subcategory')) {
            $query->where('subcategory_id', $request->subcategory);
        }

        // Search by name or description
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if (in_array($sortBy, ['name', 'price', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $products = $query->paginate(12);
        $categories = Category::all();
        $subcategories = SubCategory::all();

        return view('products.index', compact('products', 'categories', 'subcategories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'subcategory']);
        
        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for creating a new product (admin only).
     */
    public function create()
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        
        return view('products.create', compact('categories', 'subcategories'));
    }

    /**
     * Store a newly created product (admin only).
     */
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'nullable|exists:sub_categories,id',
                'stock_quantity' => 'required|integer|min:0',
                'sku' => 'required|string|max:50|unique:products,sku',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string|max:50',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            // Prepare data for creation
            $data = $request->all();
            $data['is_active'] = (bool) ($request->input('is_active') == '1');
            $data['is_featured'] = (bool) ($request->input('is_featured') == '1');

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $data['image'] = $imagePath;
            }

            // Generate slug from product name
            $data['slug'] = Str::slug($data['name']);
            
            // Ensure slug is unique
            $originalSlug = $data['slug'];
            $count = 1;
            while (Product::where('slug', $data['slug'])->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
            
            Product::create($data);
            
            return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('products.create')
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'يرجى التحقق من البيانات المدخلة');
        } catch (\Exception $e) {
            return redirect()->route('products.create')
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified product (admin only).
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        
        return view('products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified product (admin only).
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:sub_categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:50',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = (bool) ($request->input('is_active') == '1');
        $data['is_featured'] = (bool) ($request->input('is_featured') == '1');
        
        // Update slug if name has changed
        if ($product->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']);
            $originalSlug = $data['slug'];
            $count = 1;
            while (Product::where('slug', $data['slug'])->where('id', '!=', $product->id)->exists()) {
                $data['slug'] = $originalSlug . '-' . $count++;
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified product (admin only).
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
