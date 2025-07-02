<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'reviews'])->paginate(12);
        return view('products.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);
        return view('products.show', compact('product'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'age_recommendation' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'age_recommendation' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'image_url' => 'nullable|url'
        ]);

        $product->update($validated);
        return redirect()->route('products.show', $product)->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        $products = Product::where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->paginate(10); 
        
        return view('products.index', compact('products'));
    }
}