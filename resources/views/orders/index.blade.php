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

    <body class="bg-[#3E4B54] text-[#1b1b18] flex flex-col min-h-screen font-sans" style="font-family: 'Instrument Sans', sans-serif;">
        <!-- Admin Management Header -->
        <header class="w-full bg-[#2E3C46] text-white px-6 py-4 lg:px-8 shadow-lg fixed top-[0px] left-0 z-40">
            <div class="max-w-8xl mx-auto">
                <!-- Top Bar: Title + Home button -->
                <div class="flex justify-between items-center mb-2">
                    <h2 class="text-3xl text-[#e6ddd1]" style="font-family: 'Playfair Display', serif;">Admin Management</h2>
                    <a href="/" class="bg-[#e6ddd1] font-medium px-4 py-2 rounded-lg hover:bg-[#d8cfc3] transition" style="color: #690f0fff">
                        Home Page
                    </a>
                </div>

                <!-- Navigation Bar -->
                <nav class="flex gap-4">
                    <a href="{{ route('admin.products.index') }}" class="bg-[#d8d8d8] text-[#2E3C46] px-4 py-2 rounded-lg hover:bg-[#c0c0c0] transition">Products</a>
                    <a href="{{ route('admin.categories.index') }}" class="bg-[#d8d8d8] text-[#2E3C46] px-4 py-2 rounded-lg hover:bg-[#c0c0c0] transition">Categories</a>
                    <a href="{{ route('admin.orders.index') }}" class="bg-[#d8d8d8] text-[#2E3C46] px-4 py-2 rounded-lg hover:bg-[#c0c0c0] transition">Orders</a>
                    <a href="{{ route('admin.users.index') }}" class="bg-[#d8d8d8] text-[#2E3C46] px-4 py-2 rounded-lg hover:bg-[#c0c0c0] transition">Users</a>
                </nav>
            </div>
        </header>

        <br><br><br><br>

        <!-- Order Table -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-[#2E3C46] text-white shadow-lg rounded-lg overflow-hidden">
                    <div class="p-6">
                        <h2 class="text-xl font-bold">Manage Orders</h2>
                        <br>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left border border-gray-700 text-white table-auto">
                                <thead class="bg-[#3E4B54] text-white uppercase text-xs">
                                    <tr>
                                        <th class="py-3 px-4 border-b border-gray-600">ID</th>
                                        <th class="py-3 px-4 border-b border-gray-600">User</th>
                                        <th class="py-3 px-4 border-b border-gray-600">Total (฿)</th>
                                        <th class="py-3 px-4 border-b border-gray-600">Status</th>
                                        <th class="py-3 px-4 border-b border-gray-600">Payment</th>
                                        <th class="py-3 px-4 border-b border-gray-600">Time</th>
                                        <th class="py-3 px-4 border-b border-gray-600 w-[250px]">Shipping Address</th>
                                        <th class="py-3 px-4 border-b border-gray-600">Slip</th>
                                        <th class="py-3 px-4 border-b border-gray-600 text-center">Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-700">
                                    @foreach($orders as $order)
                                        <tr class="hover:bg-gray-800 transition duration-150">
                                            <td class="py-3 px-4">{{ $order->id }}</td>
                                            <td class="py-3 px-4">{{ $order->user->name }}</td>
                                            <td class="py-3 px-4">฿{{ number_format($order->total_amount, 2) }}</td>

                                            <!-- Order Status -->
                                            <td class="py-3 px-4">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $order->status === 'completed' ? 'bg-green-600 text-white' :
                                                    ($order->status === 'canceled' ? 'bg-red-600 text-white' : 'bg-yellow-500 text-black') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>

                                            <!-- Payment Status -->
                                            <td class="py-3 px-4">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $order->payment_status === 'verified' ? 'bg-green-600 text-white' :
                                                    ($order->payment_status === 'rejected' ? 'bg-red-600 text-white' : 'bg-yellow-400 text-black') }}">
                                                    {{ ucfirst($order->payment_status ?? 'Pending') }}
                                                </span>
                                            </td>

                                            <!-- Payment Time -->
                                            <td class="py-3 px-4 text-gray-300">
                                                {{ \Carbon\Carbon::parse($order->payment_time)->format('d M Y, H:i') }}
                                            </td>

                                            <!-- Full Shipping Address (No truncate, nice spacing) -->
                                            <td class="py-3 px-1 text-gray-200 break-words leading-relaxed">
                                                {{ trim($order->shipping_address) }}
                                            </td>

                                            <!-- Payment Slip -->
                                            <td class="py-3 px-4">
                                                @if($order->payment_slip_path)
                                                    <a href="{{ asset('storage/' . $order->payment_slip_path) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $order->payment_slip_path) }}"
                                                            alt="Slip Preview"
                                                            class="w-16 h-16 object-cover rounded-md border border-gray-400 hover:opacity-80 transition">
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>

                                            <!-- Actions (Now with spacing) -->
                                            <td class="py-3 px-4 text-center">
                                                <div class="flex justify-center items-center gap-3 flex-wrap">
                                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                                        class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm font-medium">
                                                        View
                                                    </a>
                                                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this order?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if($orders->isEmpty())
                                <p class="text-center text-gray-400 mt-6">No orders found.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
