@extends('layouts.tenant')

@section('content')
        @php
    $table = request()->query('table') ?? request()->query('t');
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

                @if($errors->any())
                    <div class="mb-8 bg-red-50 border-2 border-red-100 rounded-[2rem] p-6">
                        <div class="flex items-center gap-3 mb-3 text-red-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                            <h4 class="font-bold">Please check the following:</h4>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-500 font-bold space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="space-y-10">
                    @if($isTableOrder)
                        {{-- DINE-IN TABLE ORDER FORM - Simplified for dining experience --}}

                        {{-- Table Info Card --}}
                        <div
                            class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-[2.5rem] p-8 shadow-sm border border-amber-100 space-y-4">
                            <div class="flex items-center gap-5">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl flex items-center justify-center text-3xl text-white shadow-lg shadow-amber-200">
                                    🍽️
                                </div>
                                <div>
                                    <p class="text-2xl font-black italic text-amber-900">Table {{ $table }}</p>
                                    <p class="text-sm font-semibold text-amber-700">Dine-In Order</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-amber-700 bg-amber-100/60 rounded-xl px-4 py-3">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Order will be served at your table. Pay when you're ready to leave.</span>
                            </div>
                        </div>

                        {{-- Order Summary Card --}}
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="text-xl font-bold flex items-center gap-3">
                                <span
                                    class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">01</span>
                                Your Order
                            </h3>

                            <div class="space-y-3 max-h-64 overflow-y-auto" x-show="cart.length > 0">
                                <template x-for="(item, index) in cart" :key="index">
                                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 bg-slate-200 rounded-xl flex items-center justify-center text-sm font-black text-slate-600"
                                                x-text="item.qty || 1"></div>
                                            <div>
                                                <p class="font-bold text-slate-800" x-text="item.name"></p>
                                                <p class="text-sm text-slate-500" x-show="item.variant">
                                                <span x-text="item.variant?.name || ''"></span>
                                            </p>
                                            </div>
                                        </div>
                                        <p class="font-bold text-slate-900"
                                            x-text="(parseFloat(item.price) * (item.qty || 1)).toFixed(3) + ' KWD'"></p>
                                    </div>
                                </template>
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500 font-bold">Items in order</span>
                                    <span class="font-black text-lg text-slate-900"
                                        x-text="cart.reduce((sum, item) => sum + (item.qty || 1), 0) + ' items'"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Info (Optional) --}}
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="text-xl font-bold flex items-center gap-3">
                                <span
                                    class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">02</span>
                                Contact (Optional)
                            </h3>
                            <p class="text-sm text-slate-500 -mt-2">Get order updates via WhatsApp</p>

                            <div class="space-y-4">
                                <div>
                                    <x-input-label for="customer_name" :value="__('Your Name')"
                                        class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                                    <input type="text" name="customer_name"
                                        class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                        placeholder="How should we call you?">
                                </div>
                                <div>
                                    <x-input-label for="customer_mobile" :value="__('Mobile Number')"
                                        class="text-xs uppercase tracking-widest font-bold text-slate-400 mb-2" />
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">+965</span>
                                        <input type="tel" name="customer_mobile"
                                            class="w-full bg-slate-50 border-none rounded-2xl p-4 pl-16 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                            placeholder="Optional - for updates" maxlength="8">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Special Instructions --}}
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-100 space-y-6">
                            <h3 class="text-xl font-bold flex items-center gap-3">
                                <span
                                    class="w-10 h-10 bg-brand-100 text-brand-600 rounded-xl flex items-center justify-center text-sm">03</span>
                                Special Requests
                            </h3>
                            <textarea name="notes" rows="3"
                                class="w-full bg-slate-50 border-none rounded-2xl p-4 focus:ring-2 focus:ring-brand-600 font-bold shadow-inner"
                                placeholder="Allergies, preferences, or any special requests..."></textarea>
                        </div>

                        {{-- Hidden Payment Method for Dine-In - Always Pay Later --}}
                        <input type="hidden" name="payment_method" value="pay_later">
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
                        @if($isTableOrder)
                            <button type="submit"
                                class="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-[2rem] font-black italic text-lg sm:text-xl shadow-2xl shadow-amber-500/40 transform active:scale-95 transition whitespace-nowrap flex items-center gap-2">
                                <span>Send to Kitchen</span>
                                <span class="text-2xl">🍳</span>
                            </button>
                        @else
                            <button type="submit"
                                class="bg-slate-900 text-white px-6 sm:px-10 py-4 sm:py-5 rounded-2xl sm:rounded-[2rem] font-black italic text-lg sm:text-xl shadow-2xl shadow-slate-900/40 transform active:scale-95 transition whitespace-nowrap">
                                Place Order
                            </button>
                        @endif
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