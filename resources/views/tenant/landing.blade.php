@extends('layouts.tenant')

@section('content')
    <div x-data="menuState()" x-init="init()" class="pb-32">
        <!-- Hero / Banner -->
        <div class="relative h-64 md:h-80 rounded-[3rem] overflow-hidden mb-12 shadow-2xl group">
            @if(isset($settings['cover']))
                <img src="{{ asset('storage/' . $settings['cover']) }}"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            @else
                <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                    <h2 class="text-4xl font-extrabold text-white italic opacity-20">QR<span
                            class="text-brand-500">Kuwait</span></h2>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-10 left-10 md:left-14 flex items-end gap-6 text-white">
                <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-3xl p-2 shadow-xl border border-white/20">
                    @if(isset($settings['logo']))
                        <img src="{{ asset('storage/' . $settings['logo']) }}" class="w-full h-full object-contain rounded-2xl">
                    @else
                        <div
                            class="w-full h-full bg-brand-600 rounded-2xl flex items-center justify-center text-3xl font-bold italic">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="mb-2">
                    <h1 class="text-3xl md:text-5xl font-black italic">{{ $tenant->name }}</h1>
                    <p class="text-white/70 font-bold uppercase tracking-widest text-xs mt-1">{{ $tenant->type }} • Kuwait
                        City</p>
                </div>
            </div>
        </div>

        <!-- Category Tabs (Sticky) -->
        <div class="sticky top-20 z-40 -mx-4 px-4 py-4 mb-8 glass overflow-x-auto no-scrollbar scroll-smooth">
            <div class="flex gap-4 min-w-max">
                <button @click="activeCat = 'all'"
                    :class="activeCat === 'all' ? 'bg-brand-600 text-white shadow-lg shadow-brand-200' : 'bg-white text-slate-500'"
                    class="px-8 py-4 rounded-2xl font-bold transition-all duration-300">
                    All Items
                </button>
                @foreach($categories as $category)
                    <button @click="activeCat = {{ $category->id }}"
                        :class="activeCat === {{ $category->id }} ? 'bg-brand-600 text-white shadow-lg shadow-brand-200' : 'bg-white text-slate-500'"
                        class="px-8 py-4 rounded-2xl font-bold transition-all duration-300 whitespace-nowrap">
                        {{ $category->getLocalizedName() }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Items -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                @foreach($category->items as $item)
                    <div x-show="activeCat === 'all' || activeCat === {{ $category->id }}"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-500 group relative">

                        <div class="flex gap-6 h-full">
                            <div class="flex-1 space-y-3">
                                <div class="space-y-1">
                                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-brand-600 transition">
                                        {{ $item->getLocalizedName() }}
                                    </h3>
                                    <p class="text-slate-400 text-sm line-clamp-2 leading-relaxed">
                                        {{ $item->getLocalizedDescription() }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-auto">
                                    <span class="text-xl font-black text-slate-900 italic">{{ number_format($item->price, 3) }}
                                        <span
                                            class="text-sm font-bold text-slate-400 not-italic">{{ $settings['currency'] ?? 'KWD' }}</span></span>
                                    <button @click="openItem({{ $item->toJson() }})"
                                        class="w-12 h-12 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center hover:bg-brand-600 hover:text-white transition shadow-sm group-hover:shadow-md">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @if($item->image)
                                <div class="w-28 h-28 md:w-32 md:h-32 rounded-3xl overflow-hidden shadow-inner flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Item Picker Modal -->
        <div x-show="showModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-4 sm:p-0" x-cloak>
            <div @click="showModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            <div class="relative bg-white w-full max-w-lg rounded-t-[3rem] sm:rounded-[3rem] overflow-hidden shadow-2xl shadow-slate-900/20"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full sm:scale-95 sm:translate-y-0"
                x-transition:enter-end="translate-y-0 sm:scale-100">

                <template x-if="selectedItem">
                    <div class="p-8 space-y-8">
                        <div class="flex justify-between items-start">
                            <div>
                                <h2 class="text-3xl font-black italic text-slate-900" x-text="getLocName(selectedItem)">
                                </h2>
                                <p class="text-slate-500 mt-2" x-text="getLocDesc(selectedItem)"></p>
                            </div>
                            <button @click="showModal = false" class="text-slate-400 hover:text-slate-900">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modifiers / Variants Placeholder -->
                        <div class="space-y-6">
                            <template x-if="selectedItem.variants && selectedItem.variants.length">
                                <div>
                                    <h4 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-4">Choose
                                        Variant</h4>
                                    <div class="grid grid-cols-2 gap-3">
                                        <template x-for="v in selectedItem.variants">
                                            <button
                                                class="p-4 rounded-2xl border-2 border-slate-100 font-bold hover:border-brand-600 transition"
                                                x-text="v.name.en"></button>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="pt-6 border-t flex items-center justify-between">
                            <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-2xl">
                                <button
                                    class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm text-xl font-bold hover:text-brand-600">-</button>
                                <span class="text-xl font-black w-8 text-center">1</span>
                                <button
                                    class="w-10 h-10 flex items-center justify-center bg-white rounded-xl shadow-sm text-xl font-bold hover:text-brand-600">+</button>
                            </div>
                            <button @click="addToCart(); showModal = false"
                                class="bg-brand-600 text-white px-8 py-4 rounded-2xl font-black italic shadow-xl shadow-brand-200 hover:scale-105 transition">
                                Add to Cart • <span x-text="parseFloat(selectedItem.price).toFixed(3)"></span>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Sticky Cart Button -->
        <div x-show="cart.length > 0" x-transition
            class="fixed bottom-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-sm px-6">
            <a href="{{ route('tenant.checkout', $tenant->slug) }}"
                class="w-full bg-slate-900 text-white p-6 rounded-[2rem] flex justify-between items-center shadow-2xl shadow-slate-900/40 hover:scale-[1.02] transition active:scale-95">
                <div class="flex items-center gap-4">
                    <span class="w-10 h-10 bg-brand-600 rounded-xl flex items-center justify-center font-black"
                        x-text="cartTotalQty()"></span>
                    <span class="font-bold text-lg italic">View Your Order</span>
                </div>
                <span class="text-2xl font-black italic"><span x-text="cartTotalValue().toFixed(3)"></span> <span
                        class="text-xs font-bold text-slate-400">KWD</span></span>
            </a>
        </div>
    </div>

    <script>
        function menuState() {
            return {
                activeCat: 'all',
                showModal: false,
                selectedItem: null,
                cart: [],
                locale: '{{ app()->getLocale() }}',
                tenantSlug: '{{ $tenant->slug }}',

                init() {
                    const savedCart = localStorage.getItem('cart_' + this.tenantSlug);
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                    }
                },

                openItem(item) {
                    this.selectedItem = item;
                    this.showModal = true;
                },

                getLocName(item) {
                    return item.name[this.locale] || item.name['en'];
                },

                getLocDesc(item) {
                    return item.description ? (item.description[this.locale] || item.description['en']) : '';
                },

                addToCart() {
                    const existing = this.cart.find(i => i.id === this.selectedItem.id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({
                            id: this.selectedItem.id,
                            name: this.getLocName(this.selectedItem),
                            price: this.selectedItem.price,
                            qty: 1
                        });
                    }
                    this.saveCart();
                    this.showModal = false;
                },

                saveCart() {
                    localStorage.setItem('cart_' + this.tenantSlug, JSON.stringify(this.cart));
                },

                cartTotalQty() {
                    return this.cart.reduce((sum, item) => sum + item.qty, 0);
                },

                cartTotalValue() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                }
            }
        }
    </script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
@endsection