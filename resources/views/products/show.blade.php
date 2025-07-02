@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                <div class="md:flex-shrink-0">
                    @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-48 w-full object-cover md:h-full md:w-48">
                    @else
                    <div class="h-48 w-full md:h-full md:w-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500">No image available</span>
                    </div>
                    @endif
                </div>
                
                <div class="p-8">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h1>
                            <p class="mt-2 text-gray-600">{{ $product->description }}</p>
                        </div>
                        <span class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                    </div>

                    <div class="mt-6 border-t pt-4">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-700">Category:</span>
                            <span class="text-blue-600">{{ $product->category->name }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-gray-700">Age Recommendation:</span>
                            <span>{{ $product->age_recommendation }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-700">Stock:</span>
                            <span class="{{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews Section -->
        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Customer Reviews</h2>

            @auth
            <form action="{{ route('reviews.store', $product) }}" method="POST" class="mb-8 bg-white rounded-lg p-6 shadow">
                @csrf
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-bold mb-2">Review Title</label>
                    <input type="text" name="title" id="title" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-4">
                    <label for="rating" class="block text-gray-700 font-bold mb-2">Rating</label>
                    <select name="rating" id="rating" class="w-full border rounded px-3 py-2" required>
                        @for ($i = 5; $i >= 1; $i--)
                        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-bold mb-2">Your Review</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full border rounded px-3 py-2" required></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Review</button>
            </form>
            @else
            <p class="mb-8 text-gray-600">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to leave a review.</p>
            @endauth

            <div class="space-y-4">
                @forelse ($product->reviews as $review)
                <div class="bg-white rounded-lg p-6 shadow">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="font-bold text-lg">{{ $review->title }}</h3>
                            <p class="text-gray-600 text-sm">By {{ $review->user->name }} - {{ $review->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-gray-700">{{ $review->comment }}</p>
                    
                    @can('update', $review)
                    <div class="mt-4 flex space-x-4">
                        <a href="{{ route('reviews.edit', $review) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                        </form>
                    </div>
                    @endcan
                </div>
                @empty
                <p class="text-gray-600">No reviews yet. Be the first to review this product!</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection