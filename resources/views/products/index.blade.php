@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Toys & Games</h1>
        <form action="{{ route('products.search') }}" method="GET" class="flex">
            <input type="text" name="query" placeholder="Search products..." class="px-4 py-2 border rounded-l">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">Search</button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($products as $product)
        <div class="border rounded-lg overflow-hidden shadow-lg">
            @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                <span class="text-gray-500">No image available</span>
            </div>
            @endif

            <div class="p-4">
                <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                <p class="text-gray-600 mb-2">{{ Str::limit($product->description, 100) }}</p>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">${{ number_format($product->price, 2) }}</span>
                    <span class="text-sm text-gray-500">Age: {{ $product->age_recommendation }}</span>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <span class="text-sm text-gray-500">{{ $product->category->name }}</span>
                    <a href="{{ route('products.show', $product) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
</div>
@endsection