<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'comment' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['product_id'] = $product->id;

        Review::create($validated);

        return redirect()->back()
            ->with('success', 'Review posted successfully');
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);
        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'title' => 'required|max:255',
            'comment' => 'required',
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $review->update($validated);

        return redirect()->route('products.show', $review->product)
            ->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);
        $review->delete();

        return redirect()->back()
            ->with('success', 'Review deleted successfully');
    }
}