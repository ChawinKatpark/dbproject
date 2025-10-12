<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400&display=swap" rel="stylesheet">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> 
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        
        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            
            </style>
        @endif
    </head>
    
    <body class="bg-[#8DA79C] text-[#1b1b18] flex flex-col min-h-screen font-sans" style="font-family: 'Instrument Sans', sans-serif;">
        <!-- Header -->
        <header class="w-full bg-[#5c7266] text-white px-6 py-6 lg:px-8 shadow-lg fixed top-0 left-0 z-50">
            @if (Route::has('login'))
                <nav class="max-w-8xl mx-auto flex justify-between items-center">
                    <div class="text-[2rem] lg:text-[2.5rem] tracking-wide" style="font-family: 'Playfair Display', serif;">
                        <a href="{{ url('/') }}" class="hover:text-[#e6ddd1] transition">Shopping Cart</a>
                    </div>
                </nav>
            @endif
        </header>

        <!-- 🔙 Floating Back Arrow -->
        <a href="{{ url('/') }}" 
        class="fixed top-40 left-10 bg-[#A4B5AD] text-[#2e4238] hover:bg-[#5c7266] hover:text-white rounded-full w-12 h-12 flex items-center justify-center text-3xl shadow-md transition duration-300 z-40"
        title="Go Back">
            <
        </a>

        <br>

        <div class="pt-32 px-12 lg:pl-48">
        @if ($cartItems->isEmpty())
            <div class="bg-[#e6ddd1] rounded-lg shadow-md flex flex-col items-center justify-center p-10 space-y-4 max-w-md mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-[#5c7266]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.2 6M17 13l1.2 6M6 19h12" />
                </svg>
                <p class="text-lg text-[#2e4238] font-semibold text-center">
                    Your cart is empty. <br>
                    Our products are waiting for you!
                </p>
                <a href="{{ url('/') }}" class="inline-block bg-[#5c7266] hover:bg-[#4a5c50] text-white px-6 py-2 rounded shadow-md transition">
                    Browse Products
                </a>
            </div>
        @else

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- 🛒 Cart Items -->
                <div class="lg:col-span-2 space-y-6">
                    @foreach ($cartItems as $item)
                        <div class="bg-[#D9D9D9] rounded-lg shadow-md flex items-center justify-between p-4">
                            <!-- Image -->
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-24 h-24 object-cover rounded-lg">

                            <!-- Info -->
                            <div class="flex-1 px-4">
                                <h2 class="text-lg font-bold text-[#2e4238]">{{ $item->product->name }}</h2>
                                {{-- ✅ Green price with formatting --}}
                                <p class="text-green-600 font-semibold text-lg">฿{{ number_format($item->product->price, 0) }}</p>

                                <!-- Quantity Controls -->
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="mt-2 flex items-center space-x-2">
                                    @csrf
                                    @method('PATCH')
                                    Quantity:
                                    <button type="submit" name="decrease" value="1"
                                            class="w-8 h-8 bg-[#5c7266] text-white rounded">−</button>
                                    <span class="bg-white text-black px-3 py-1 rounded">{{ $item->quantity }}</span>
                                    <button type="submit" name="increase" value="1"
                                            class="w-8 h-8 bg-[#5c7266] text-white rounded">+</button>
                                </form>
                            </div>

                            <!-- Remove -->
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                                  onsubmit="return confirm('Remove this item?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700">Remove</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="bg-gray-100 rounded-lg shadow-md p-6 max-h-[400px] overflow-y-auto">
                    <h3 class="text-xl font-bold mb-6 text-[#2e4238]">Order Summary</h3>

                    <div class="text-gray-700 text-md space-y-3">
                        {{-- ✅ List each product and subtotal --}}
                        @foreach ($cartItems as $item)
                            <div class="flex justify-between">
                                <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                                <span>฿{{ number_format($item->product->price * $item->quantity, 0) }}</span>
                            </div>
                        @endforeach

                        <hr class="my-4 border-gray-300">

                        {{-- ✅ Total --}}
                        @php
                            $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
                        @endphp

                        <div class="flex justify-between text-lg font-bold text-[#2e4238]">
                            <span>Total</span>
                            <span>฿{{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.show') }}"
                    class="block w-full mt-6 text-center bg-[#2e3c46] text-white py-2 rounded hover:bg-[#1c2a30] transition">
                        Check Out
                    </a>
                </div>

            </div>
        @endif
    </div>
    </body>
    <br>
</html>
