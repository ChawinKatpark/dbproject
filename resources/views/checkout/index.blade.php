<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400&display=swap" rel="stylesheet" />
</head>
<body
    class="min-h-screen flex items-center justify-center py-10 bg-[#8DA79C] text-[#1b1b18] flex-col"
>
    <!-- 🔙 Floating Back Arrow -->
    <a
        href="{{ url('/cart') }}"
        class="fixed top-20 left-20 bg-[#A4B5AD] text-[#2e4238] hover:bg-[#5c7266] hover:text-white rounded-full w-12 h-12 flex items-center justify-center text-3xl shadow-md transition duration-300 z-40"
        title="Go Back"
        >&lt;</a
    >

    <div class="container mx-auto max-w-lg p-8 bg-[#D6D6D6] backdrop-blur-md rounded-2xl">
        <h1
            class="text-4xl font-extrabold mb-8 text-[#4A6156] text-center"
            style="font-family: 'Playfair Display', serif;"
        >
            Checkout
        </h1>

        <form
            method="POST"
            action="{{ route('checkout.process') }}"
            enctype="multipart/form-data"
            class="space-y-6"
            id="checkout-form"
        >
            @csrf

            <!-- Order Summary -->
            <div class="pt-4 border-t border-[#4A6156]">
                <h2 class="text-2xl font-bold text-[#4A6156] mb-3">Order Summary</h2>
                <ul id="order-summary" class="list-disc list-inside text-[#4A6156]">
                    @foreach($cartItems as $cartItem)
                        <li>
                            {{ $cartItem->product->name }} × {{ $cartItem->quantity }}
                            — ฿{{ number_format($cartItem->product->price * $cartItem->quantity, 2) }}
                        </li>
                    @endforeach
                </ul>
                <p class="mt-3 font-semibold text-[#4A6156] text-lg">
                    Total: ฿{{ number_format($cartItems->sum(fn($item) => $item->product->price * $item->quantity), 2) }}
                </p>
            </div>

            <!-- Shipping Fields -->
            <div>
                <label for="shipping_address" class="block font-semibold text-[#4A6156] mb-1">Shipping Address</label>
                <input
                    type="text"
                    name="shipping_address"
                    id="shipping_address"
                    class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                    required
                />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="city" class="block font-semibold text-[#4A6156] mb-1">City</label>
                    <input
                        type="text"
                        name="city"
                        id="city"
                        class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                        required
                    />
                </div>
                <div>
                    <label for="state" class="block font-semibold text-[#4A6156] mb-1">State</label>
                    <input
                        type="text"
                        name="state"
                        id="state"
                        class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                        required
                    />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="postal_code" class="block font-semibold text-[#4A6156] mb-1">Postal Code</label>
                    <input
                        type="text"
                        name="postal_code"
                        id="postal_code"
                        class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                        required
                    />
                </div>
                <div>
                    <label for="country" class="block font-semibold text-[#4A6156] mb-1">Country</label>
                    <input
                        type="text"
                        name="country"
                        id="country"
                        class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                        required
                    />
                </div>
            </div>

            <!-- Payment Section -->
            <div class="text-center">
                <p class="text-[#4A6156] font-semibold mb-2">
                    Scan this PromptPay QR code to make your payment:
                </p>
                <img
                    src="{{ asset('images/qrcode.png') }}"
                    alt="PromptPay QR Code"
                    class="mx-auto w-56 h-auto rounded-lg shadow-md"
                />
            </div>

            <div>
                <label for="payment_slip" class="block font-semibold text-[#4A6156] mb-1 mt-2">Upload Payment Slip</label>
                <input
                    type="file"
                    name="payment_slip"
                    id="payment_slip"
                    accept="image/*"
                    class="w-full border border-[#4A6156] rounded-md p-3 bg-white focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                    required
                />
            </div>

            <div>
                <label for="payment_time" class="block font-semibold text-[#4A6156] mb-1">Payment Date &amp; Time</label>
                <input
                    type="datetime-local"
                    name="payment_time"
                    id="payment_time"
                    class="w-full border border-[#4A6156] rounded-md p-3 focus:ring-2 focus:ring-[#4A6156] focus:outline-none"
                    required
                />
            </div>

            <!-- Submit -->
            <button
                id="submit-button"
                type="submit"
                class="w-full py-3 text-lg font-semibold rounded-md bg-gradient-to-r from-green-500 to-green-600 text-white shadow-lg hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-[1.02]"
            >
                Submit Order
            </button>
        </form>

        <!-- Error Box -->
        @if ($errors->any())
            <div class="mt-6 p-4 bg-red-100 border border-red-400 text-red-800 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Fade In Animation -->
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.5s ease-in-out;
        }
    </style>

    @if(session('order_success'))
    <script>
        alert(@json(session('order_success')));
        window.location.href = "{{ url('/') }}";
    </script>
    @endif

</body>
</html>