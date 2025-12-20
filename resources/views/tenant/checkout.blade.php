@extends('layouts.tenant')

@section('content')
    @php
        $table = request()->query('table');
        $isTableOrder = !empty($table);
    @endphp

    <div x-data="checkoutState()" x-init="init()" class="pb-40 max-w-2xl mx-auto">
        <div class="mb-10 mt-8">
            <h1 class="text-4xl font-black italic text-slate-900 mb-2">
                @if($isTableOrder)
                    Table {{ $table }} Order
                @else
                    Checkout
                @endif
            </h1>
            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">
                @if($isTableOrder)
                    Add items anytime before requesting the bill
                @else
                    Complete your order
                @endif
            </p>
        </div>

        <form action="{{ route('tenant.checkout.store', $tenant->slug) }}" method="POST" id="checkoutForm">
            @csrf
            <input type="hidden" name="cart_data" :value="JSON.stringify(cart)">
            @if($isTableOrder)
                <input type="hidden" name="table_number" value="{{ $table }}">
                <input type="hidden" name="delivery_type" value="dine_in">
            @endif

            <div class="space-y-10">
                @if($isTableOrder)
                    {{-- SIMPLIFIED TABLE ORDER FORM --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <div class="flex items-center gap-4 p-4 bg-amber-50 rounded-2xl border border-amber-200">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-2xl">🍽️</div>
                            <div>
                                <p class="font-bold text-amber-800">Table {{ $table }}</p>
                                <p class="text-sm text-amber-600">Your order will be served at your table</p>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="customer_mobile" :value="__('Mobile Number (for updates)')"
                                class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">+965</span>
                                <input type="tel" name="customer_mobile"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-16 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                    placeholder="Phone Number" required maxlength="8">
                            </div>
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Special Instructions (Optional)')"
                                class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                            <textarea name="notes" rows="2"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                placeholder="Any allergies or preferences?"></textarea>
                        </div>
                    </div>

                    {{-- Payment for Table Order --}}
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">💳</span>
                            Payment
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="pay_later" x-model="paymentMethod"
                                    class="sr-only peer" checked>
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center">
                                    <span class="text-3xl mb-2 block">🧾</span>
                                    <span class="font-bold block">Pay Later</span>
                                    <span class="text-xs text-slate-400">At checkout</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="knet" x-model="paymentMethod"
                                    class="sr-only peer">
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center">
                                    <span class="text-3xl mb-2 block">💳</span>
                                    <span class="font-bold block">KNET</span>
                                    <span class="text-xs text-slate-400">Pay now</span>
                                </div>
                            </label>
                        </div>
                    </div>
                @else
                    {{-- FULL DELIVERY/PICKUP FORM --}}
                    <!-- Contact Info -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">01</span>
                            Contact Information
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="customer_name" :value="__('Full Name')"
                                    class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                                <input type="text" name="customer_name"
                                    class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                    placeholder="Enter your name" required>
                            </div>
                            <div>
                                <x-input-label for="customer_mobile" :value="__('Mobile Number')"
                                    class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">+965</span>
                                    <input type="tel" name="customer_mobile"
                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-16 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                        placeholder="Phone Number" required maxlength="8">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delivery Method -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">02</span>
                            Delivery Method
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="delivery_type" value="pickup" x-model="deliveryType"
                                    class="sr-only peer">
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 group-peer-checked:text-brand-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    <span class="font-bold block">Pickup</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="delivery_type" value="delivery" x-model="deliveryType"
                                    class="sr-only peer">
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center text-slate-500">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 group-peer-checked:text-brand-600"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span class="font-bold block">Delivery</span>
                                </div>
                            </label>
                        </div>

                        <!-- Address Fields (Conditional) -->
                        <div x-show="deliveryType === 'delivery'" x-transition class="space-y-4 pt-4 border-t">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="area" :value="__('Area')"
                                        class="text-[10px] uppercase font-bold text-slate-400 mb-1" />
                                    <input type="text" name="area"
                                        class="w-full bg-slate-50 border-none rounded-xl p-3 font-bold shadow-inner text-sm"
                                        placeholder="e.g. Salmiya">
                                </div>
                                <div>
                                    <x-input-label for="block" :value="__('Block')"
                                        class="text-[10px] uppercase font-bold text-slate-400 mb-1" />
                                    <input type="text" name="block"
                                        class="w-full bg-slate-50 border-none rounded-xl p-3 font-bold shadow-inner text-sm"
                                        placeholder="e.g. 5">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="street" :value="__('Street')"
                                        class="text-[10px] uppercase font-bold text-slate-400 mb-1" />
                                    <input type="text" name="street"
                                        class="w-full bg-slate-50 border-none rounded-xl p-3 font-bold shadow-inner text-sm"
                                        placeholder="e.g. 102">
                                </div>
                                <div>
                                    <x-input-label for="house" :value="__('House/Flat')"
                                        class="text-[10px] uppercase font-bold text-slate-400 mb-1" />
                                    <input type="text" name="house"
                                        class="w-full bg-slate-50 border-none rounded-xl p-3 font-bold shadow-inner text-sm"
                                        placeholder="e.g. 24">
                                </div>
                            </div>
                            <div>
                                <x-input-label for="landmark" :value="__('Landmark')"
                                    class="text-[10px] uppercase font-bold text-slate-400 mb-1" />
                                <input type="text" name="landmark"
                                    class="w-full bg-slate-50 border-none rounded-xl p-3 font-bold shadow-inner text-sm"
                                    placeholder="e.g. Near Sultan Center">
                            </div>
                            <!-- Location Fetch Button -->
                            <div class="pt-2">
                                <input type="hidden" name="latitude" x-model="latitude">
                                <input type="hidden" name="longitude" x-model="longitude">
                                <input type="hidden" name="location_url" x-model="locationUrl">

                                <button type="button" @click="fetchLocation()" :disabled="fetchingLocation"
                                    class="w-full flex items-center justify-center gap-3 p-4 rounded-2xl border-2 border-dashed transition-all"
                                    :class="locationUrl ? 'border-green-400 bg-green-50 text-green-700' : 'border-slate-200 bg-slate-50 text-slate-600 hover:border-brand-400 hover:bg-brand-50'">
                                    <template x-if="fetchingLocation">
                                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="!fetchingLocation && !locationUrl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="!fetchingLocation && locationUrl">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </template>
                                    <span class="font-bold"
                                        x-text="fetchingLocation ? 'Getting Location...' : (locationUrl ? 'Location Captured ✓' : 'Get My Location')"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">03</span>
                            Payment Method
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="cash" x-model="paymentMethod"
                                    class="sr-only peer" checked>
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-brand-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span class="font-bold block">Cash</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="payment_method" value="knet" x-model="paymentMethod"
                                    class="sr-only peer">
                                <div
                                    class="p-6 rounded-3xl border-2 border-slate-100 peer-checked:border-brand-600 peer-checked:bg-brand-50 transition-all text-center">
                                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-400 peer-checked:text-brand-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    <span class="font-bold block">KNET</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                        <h3 class="text-xl font-bold flex items-center gap-3">
                            <span
                                class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">04</span>
                            Order Notes
                        </h3>
                        <textarea name="notes" rows="3"
                            class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                            placeholder="Any special instructions?"></textarea>
                    </div>
                @endif

                <!-- Bot Protection (Honeypot) -->
                <div class="hidden" aria-hidden="true">
                    <input type="text" name="verify_token" value="" tabindex="-1" autocomplete="off">
                </div>
            </div>

            <!-- Sticky Summary & Submit - Fixed for mobile -->
            <div
                class="fixed bottom-0 left-0 right-0 p-4 sm:p-6 bg-white/95 backdrop-blur-xl border-t z-50 safe-area-bottom">
                <div class="max-w-2xl mx-auto flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total</p>
                        <p class="text-2xl sm:text-3xl font-black italic text-slate-900">
                            <span x-text="total.toFixed(3)"></span>
                            <span class="text-sm sm:text-base not-italic text-slate-400 font-bold">KWD</span>
                        </p>
                    </div>
                    <button type="submit"
                        class="bg-slate-900 text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-[2rem] font-black italic text-lg sm:text-xl shadow-2xl shadow-slate-900/40 transform active:scale-95 transition whitespace-nowrap">
                        @if($isTableOrder)
                            Send Order
                        @else
                            Place Order
                        @endif
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function checkoutState() {
            return {
                cart: [],
                deliveryType: 'pickup',
                paymentMethod: '{{ $isTableOrder ? "pay_later" : "cash" }}',
                total: 0,

                // Location state
                latitude: '',
                longitude: '',
                locationUrl: '',
                fetchingLocation: false,
                locationError: '',

                init() {
                    const savedCart = localStorage.getItem('cart_{{ $tenant->slug }}');
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                        this.calculateTotal();
                    } else {
                        window.location.href = '{{ route("tenant.public", $tenant->slug) }}';
                    }
                },

                calculateTotal() {
                    this.total = this.cart.reduce((sum, item) => sum + (parseFloat(item.price) * (item.qty || 1)), 0);
                },

                fetchLocation() {
                    if (!navigator.geolocation) {
                        this.locationError = 'Geolocation is not supported by your browser';
                        return;
                    }

                    this.fetchingLocation = true;
                    this.locationError = '';

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.locationUrl = `https://www.google.com/maps?q=${this.latitude},${this.longitude}`;
                            this.fetchingLocation = false;
                        },
                        (error) => {
                            this.fetchingLocation = false;
                            this.locationError = 'Could not get location. Please try again.';
                        },
                        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                    );
                }
            }
        }
    </script>
@endsection