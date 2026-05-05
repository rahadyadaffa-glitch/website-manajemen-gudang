<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $query = Product::with(['category', 'variants']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('variants', function($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                  });
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(15);

        if ($request->ajax()) {
            return view('superadmin.products.partials._product_list', compact('products'))->render();
        }

        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();

        return view('superadmin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        return view('superadmin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            // Variant data
            'sku' => 'required|string|max:50|unique:product_variants,sku',
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode',
            'weight_value' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|max:20',
            'unit' => 'required|string|max:20',
            'pcs_per_dus' => 'required|integer|min:1',
            'min_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $variantData = [
            'product_id' => $product->id,
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'],
            'weight_value' => $validated['weight_value'],
            'weight_unit' => $validated['weight_unit'],
            'unit' => $validated['unit'],
            'pcs_per_dus' => $validated['pcs_per_dus'],
            'min_stock_threshold' => $validated['min_stock_threshold'],
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $variantData['image_path'] = $path;
        }

        $product->variants()->create($variantData);

        return redirect()->route('superadmin.products.index')->with('success', 'Produk dan varian default berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::whereNull('parent_id')->with('children')->orderBy('name')->get();
        $variant = $product->variants()->first(); // Edit the first variant for now
        return view('superadmin.products.edit', compact('product', 'categories', 'variant'));
    }

    public function update(Request $request, Product $product)
    {
        $variant = $product->variants()->first();
        
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:200',
            'description' => 'nullable|string',
            // Variant data
            'sku' => 'required|string|max:50|unique:product_variants,sku,' . $variant->id,
            'barcode' => 'nullable|string|max:100|unique:product_variants,barcode,' . $variant->id,
            'weight_value' => 'nullable|numeric|min:0',
            'weight_unit' => 'nullable|string|max:20',
            'unit' => 'required|string|max:20',
            'pcs_per_dus' => 'required|integer|min:1',
            'min_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        $variantData = [
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'],
            'weight_value' => $validated['weight_value'],
            'weight_unit' => $validated['weight_unit'],
            'unit' => $validated['unit'],
            'pcs_per_dus' => $validated['pcs_per_dus'],
            'min_stock_threshold' => $validated['min_stock_threshold'],
        ];

        if ($request->hasFile('image')) {
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }
            $path = $request->file('image')->store('products', 'public');
            $variantData['image_path'] = $path;
        }

        $variant->update($variantData);

        return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();
        return redirect()->route('superadmin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
