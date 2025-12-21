@extends('layouts.tenant')

@section('content')
    <div x-data="menuState()" x-init="init()" x-cloak 
        @open-cart.window="showCart = true"
        @open-search.window="showSearch = true"
        class="pb-32 relative">

        <!-- Search Drawer (Minimal OCD) -->
        <div x-show="showSearch" class="fixed inset-0 z-[100] flex items-start justify-center pt-24 px-4" x-cloak @keydown.escape.window="showSearch = false">
            <div @click="showSearch = false"
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300"></div>
            <div class="relative bg-white w-full max-w-xl rounded-2xl overflow-hidden shadow-2xl border border-slate-100"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 -translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div class="relative flex items-center p-4 border-b border-slate-50">
                    <svg class="absolute left-8 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="searchQuery" x-ref="searchInput" @input="showSearch = true"
                        class="w-full bg-slate-50 border-none rounded-xl py-4 pl-12 pr-4 text-sm font-bold text-slate-900 placeholder-slate-400 focus:bg-white transition"
                        placeholder="{{ __('Search menu...') }}" autofocus>
                </div>

                <div class="max-h-[50vh] overflow-y-auto no-scrollbar p-4 space-y-1">
                    <template x-if="filteredItems.length === 0">
                        <div class="text-center py-12 text-slate-300 text-xs font-bold uppercase tracking-widest">{{ __('No items found') }}
                        </div>
                    </template>
                    <template x-for="item in filteredItems" :key="item.id">
                        <div @click="openItem(item); showSearch = false"
                            class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition cursor-pointer group">
                            <div class="w-12 h-12 rounded-lg bg-slate-100 overflow-hidden flex-shrink-0 border border-slate-100">
                                <img :src="item.image_url" class="w-full h-full object-cover" x-show="item.image_url">
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-slate-900 text-sm" x-text="getLocName(item)"></div>
                                <div class="text-[10px] text-slate-400 font-medium truncate w-48" x-text="getLocDesc(item)"></div>
                            </div>
                            <div class="text-sm font-bold text-slate-900" x-text="formatPrice(item.price)"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Hero / Banner -->
        <!-- Clean Minimal Menu Section -->
        <div class="space-y-12">
            <!-- Simplified Banner -->
            @if($tenant->cover_url)
                <div class="h-48 md:h-64 rounded-2xl overflow-hidden grayscale-[0.2] border border-slate-100">
                    <img src="{{ $tenant->cover_url }}" class="w-full h-full object-cover">
                </div>
            @endif

            <!-- Category Navigation (OCD Clean) -->
            <div class="sticky top-16 z-40 bg-white/80 backdrop-blur-md -mx-6 px-6 py-4 border-b border-slate-100">
                <div class="flex gap-2 overflow-x-auto no-scrollbar">
                    <button @click="activeCat = 'all'"
                        :class="activeCat === 'all' ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-400 hover:text-slate-900'"
                        class="px-5 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">
                        {{ __('All') }}
                    </button>
                    @foreach($categories as $category)
                        <button @click="activeCat = {{ $category->id }}"
                            :class="activeCat === {{ $category->id }} ? 'bg-slate-900 text-white' : 'bg-slate-50 text-slate-400 hover:text-slate-900'"
                            class="px-5 py-2 rounded-lg font-bold text-[10px] uppercase tracking-widest transition-all whitespace-nowrap">
                            {{ $category->getLocalizedName() }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

                <!-- OCD Clean Menu Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($categories as $category)
                        @foreach($category->items as $item)
                            <div x-show="activeCat === 'all' || activeCat === {{ $category->id }}"
                                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translateY(10px)"
                                x-transition:enter-end="opacity-100 translateY(0)"
                                class="group bg-white rounded-3xl p-6 border border-slate-200 hover:border-slate-900 hover:shadow-xl hover:shadow-slate-200 transition-all duration-300 cursor-pointer flex flex-col justify-between @if(!$item->is_active) opacity-50 @endif"
                                @click="@if($item->is_active) openItem({{ $item->toJson() }}) @endif">

                                <div class="flex gap-5">
                                    @if($item->image)
                                        <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 bg-slate-50 border border-slate-100">
                                            <img src="{{ $item->image_url }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-bold text-slate-900 mb-1 truncate">{{ $item->getLocalizedName() }}</h3>
                                        <p class="text-slate-400 text-xs font-medium leading-relaxed line-clamp-2">
                                            {{ $item->getLocalizedDescription() }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-6 pt-4 border-t border-slate-50">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-[10px] uppercase tracking-widest text-slate-400 font-bold mb-0.5">{{ __('Price') }}</span>
                                        <span class="text-xl font-bold text-slate-900">
                                            {{ number_format($item->price, 3) }}
                                            <span
                                                class="text-[10px] font-medium text-slate-400 uppercase ml-0.5">{{ $settings['currency'] ?? 'KWD' }}</span>
                                        </span>
                                    </div>
                                    @if($item->is_active)
                                        <div
                                            class="w-10 h-10 rounded-xl bg-slate-50 text-slate-900 flex items-center justify-center group-hover:bg-slate-900 group-hover:text-white transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <span
                                            class="text-[10px] font-bold uppercase text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">{{ __('Sold Out') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>

                <!-- Mini Cart Summary (Fixed Bottom) -->
                <div x-show="cart.length > 0 && !showCart" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="translate-y-full opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
                    class="fixed bottom-20 left-6 right-6 z-40 sm:bottom-6 sm:left-auto sm:right-6 sm:w-80" x-cloak>
                    <div @click="showCart = true"
                        class="bg-slate-900 text-white p-4 rounded-2xl shadow-2xl flex items-center justify-between cursor-pointer group hover:scale-[1.02] transition-transform">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center font-bold text-xs"
                                x-text="cart.reduce((s, i) => s + i.qty, 0)"></div>
                            <span class="text-xs font-bold uppercase tracking-widest">{{ __('View Order') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold" x-text="formatPrice(calculateCartTotal())"></span>
                        </div>
                    </div>
                    </div>

                <!-- Item Picker Modal (Minimal OCD Design) -->
                <div x-show="showModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-cloak
                    @keydown.escape.window="showModal = false">
                    <div @click="showModal = false"
                        class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300"
                        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"></div>
                    <div class="relative bg-white w-full max-w-lg max-h-[90vh] rounded-[2.5rem] overflow-hidden shadow-2xl flex flex-col border border-slate-100"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100">

                        <template x-if="selectedItem">
                            <div class="flex-1 flex flex-col overflow-y-auto no-scrollbar">
                                <!-- Tiny Close Button -->
                                <button @click="showModal = false"
                                    class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-white/80 backdrop-blur border border-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                        </path>
                                    </svg>
                                </button>

                                <div class="p-8 space-y-8">
                                    <div class="text-center">
                                        <h2 class="text-2xl font-bold text-slate-900" x-text="getLocName(selectedItem)"></h2>
                                        <p class="text-slate-400 mt-2 text-sm font-medium" x-text="getLocDesc(selectedItem)"></p>
                                    </div>

                                    <!-- Variants -->
                                    <template x-if="selectedItem.variants && selectedItem.variants.length">
                                        <div class="space-y-4">
                                            <div
                                                class="flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-50 pb-2">
                                                <span>{{ __('Required Option') }}</span>
                                            </div>
                                            <div class="space-y-2">
                                                <template x-for="v in selectedItem.variants" :key="v.id">
                                                    <div @click="activeVariant = v"
                                                        class="px-5 py-4 rounded-2xl border transition-all duration-200 cursor-pointer flex justify-between items-center"
                                                        :class="activeVariant && activeVariant.id === v.id ? 'border-slate-900 bg-slate-900 text-white shadow-lg' : 'border-slate-100 bg-slate-50 text-slate-600 hover:border-slate-200'">
                                                        <span class="font-bold text-sm" x-text="getLocName(v)"></span>
                                                        <span class="font-bold text-xs" x-text="'+' + formatPrice(v.price)"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Modifiers -->
                                    <template x-if="selectedItem.modifiers && selectedItem.modifiers.length">
                                        <div class="space-y-4">
                                            <div class="text-[10px] font-bold uppercase tracking-widest text-slate-400 border-b border-slate-50 pb-2">
                                                <span>{{ __('Extras') }}</span>
                                            </div>
                                            <div class="grid grid-cols-2 gap-2">
                                                <template x-for="m in selectedItem.modifiers" :key="m.id">
                                                    <div @click="toggleModifier(m)"
                                                        class="px-4 py-3 rounded-xl border transition-all duration-200 cursor-pointer flex flex-col gap-1"
                                                        :class="isModifierActive(m) ? 'border-slate-900 bg-slate-900 text-white shadow-md' : 'border-slate-100 bg-slate-50 text-slate-600 hover:border-slate-200'">
                                                        <span class="font-bold text-xs" x-text="getLocName(m)"></span>
                                                        <span class="font-medium text-[10px] opacity-70"
                                                            x-text="'+' + formatPrice(m.price)"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Item Notes -->
                                    <div class="space-y-3">
                                        <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">
                                            {{ __('Special request?') }}
                                        </h4>
                                        <input type="text" x-model="itemNote"
                                            class="w-full rounded-xl border-slate-100 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-600 focus:bg-white focus:ring-1 focus:ring-slate-900 focus:border-slate-900 transition"
                                            placeholder="{{ __('e.g No onions') }}">
                                    </div>
                                    </div>
                                    </div>
                                    </template>

                        <div class="p-8 border-t border-slate-50 bg-slate-50 flex items-center justify-between gap-6">
                            <div class="flex items-center gap-4 bg-white rounded-xl p-1 border border-slate-100">
                                <button @click="if(modalQty > 1) modalQty--"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-900 font-bold transition-colors">-</button>
                                <span class="text-sm font-bold w-4 text-center text-slate-900" x-text="modalQty"></span>
                                <button @click="modalQty++"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-900 font-bold transition-colors">+</button>
                    </div>
                    <button @click="addToCart"
                        class="flex-1 bg-slate-900 text-white h-12 rounded-xl text-sm font-bold tracking-tight hover:shadow-lg hover:translate-y-[-1px] transition-all active:scale-[0.98]">
                        <span x-text="'Add - ' + formatPrice(calculateModalTotal())"></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Cart Drawer (Minimal OCD Design) -->
        <div x-show="showCart" class="fixed inset-0 z-[110] flex items-center justify-end" x-cloak
            @keydown.escape.window="showCart = false">
            <div @click="showCart = false"
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity duration-300"></div>
            <div class="relative bg-white w-full max-w-md h-full shadow-2xl flex flex-col border-l border-slate-100"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0">

                <div class="p-8 flex justify-between items-center border-b border-slate-50">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">{{ __('Your Order') }}</h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1"
                            x-text="cart.length + ' {{ __('Items') }}'"></p>
                    </div>
                    <button @click="showCart = false" class="text-slate-400 hover:text-slate-900 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto no-scrollbar p-8 space-y-8">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-40">
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Empty order') }}</h3>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.cartKey">
                        <div class="flex flex-col gap-2 group">
                            <div class="flex justify-between items-start">
                                <h4 class="font-bold text-slate-900 text-sm" x-text="item.name"></h4>
                                <span class="font-bold text-slate-900 text-sm" x-text="formatPrice(item.price * item.qty)"></span>
                                </div>
                            <div class="flex flex-wrap gap-2 text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                <template x-if="item.variant">
                                    <span x-text="item.variant.name"></span>
                                    </template>
                                    <template x-for="m in item.modifiers" :key="m.id">
                                    <span x-text="m.name"></span>
                                    </template>
                                    </div>
                            <div class="flex justify-between items-center mt-2 pb-6 border-b border-slate-50">
                                <div class="flex items-center gap-4 bg-slate-50 rounded-lg px-2 py-1">
                                    <button @click="updateCartQty(index, -1)"
                                        class="w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-900 font-bold">-</button>
                                    <span class="text-xs font-bold w-4 text-center text-slate-900" x-text="item.qty"></span>
                                    <button @click="updateCartQty(index, 1)"
                                        class="w-6 h-6 flex items-center justify-center text-slate-400 hover:text-slate-900 font-bold">+</button>
                                </div>
                                <button @click="removeFromCart(index)"
                                    class="text-[10px] font-bold text-slate-300 hover:text-red-500 transition-colors uppercase tracking-widest">
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-8 pb-24 sm:pb-8 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('Total Order') }}</span>
                        <span class="text-2xl font-bold text-slate-900" x-text="formatPrice(calculateCartTotal())"></span>
                    </div>
                    <a href="{{ route('tenant.checkout', [$tenant->slug, 'table' => request()->query('table') ?? request()->query('t')]) }}"
                        class="flex-1 bg-slate-900 text-white h-14 flex items-center justify-center rounded-xl text-sm font-bold tracking-tight hover:shadow-lg transition-all"
                        :class="cart.length === 0 ? 'opacity-50 pointer-events-none' : ''">
                        {{ __('Checkout Now') }}
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="fixed top-10 left-1/2 -translate-x-1/2 z-[200] w-full max-w-md px-6">
                <div class="bg-slate-900 text-white p-6 rounded-3xl shadow-2xl flex items-center gap-4">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </div>

    <script>
        function menuState() {
            return {
                activeCat: 'all',
                showModal: false,
                selectedItem: null,
                showCart: false,
                showSearch: false,
                searchQuery: '',
                cart: [],
                modalQty: 1,
                activeVariant: null,
                activeModifiers: [],
                itemNote: '',
                locale: '{{ app()->getLocale() }}',
                tenantSlug: '{{ $tenant->slug }}',

                init() {
                    const savedCart = localStorage.getItem('cart_' + this.tenantSlug);
                    if (savedCart) {
                        this.cart = JSON.parse(savedCart);
                        this.syncGlobalCart();
                    }
                },

                get filteredItems() {
                    if (!this.searchQuery) return [];
                    const q = this.searchQuery.toLowerCase();
                    const allItems = [];
                    @foreach($categories as $category)
                        @foreach($category->items as $item)
                            allItems.push({!! $item->toJson() !!});
                        @endforeach
                    @endforeach
                    return allItems.filter(i => {
                        const nameEn = (i.name.en || '').toLowerCase();
                        const nameAr = (i.name.ar || '').toLowerCase();
                        return nameEn.includes(q) || nameAr.includes(q);
                    }).slice(0, 10);
                },

                openItem(item) {
                    this.selectedItem = item;
                    this.modalQty = 1;
                    this.activeVariant = item.variants && item.variants.length ? item.variants[0] : null;
                    this.activeModifiers = [];
                    this.itemNote = '';
                    this.showModal = true;
                },

                getLocName(obj) {
                    if (!obj || !obj.name) return '';
                    return obj.name[this.locale] || obj.name['en'] || '';
                },

                getLocDesc(obj) {
                    if (!obj || !obj.description) return '';
                    return obj.description[this.locale] || obj.description['en'] || '';
                },

                formatPrice(price) {
                    const num = parseFloat(price);
                    return isNaN(num) ? '0.000' : num.toFixed(3);
                },

                toggleModifier(mod) {
                    const idx = this.activeModifiers.findIndex(m => m.id === mod.id);
                    if (idx > -1) {
                        this.activeModifiers.splice(idx, 1);
                    } else {
                        this.activeModifiers.push(mod);
                    }
                },

                isModifierActive(mod) {
                    return this.activeModifiers.some(m => m.id === mod.id);
                },

                calculateModalTotal() {
                    if (!this.selectedItem) return 0;
                    let base = parseFloat(this.selectedItem.price);
                    if (this.activeVariant) base += parseFloat(this.activeVariant.price);
                    this.activeModifiers.forEach(m => base += parseFloat(m.price));
                    return base * this.modalQty;
                },

                addToCart() {
                    if (!this.selectedItem) return;

                    // Validate variant selection if required
                    if (this.selectedItem.variants && this.selectedItem.variants.length && !this.activeVariant) {
                        alert('Please select an option');
                        return;
                    }

                    let finalPrice = parseFloat(this.selectedItem.price) || 0;
                    if (this.activeVariant) finalPrice += parseFloat(this.activeVariant.price) || 0;
                    this.activeModifiers.forEach(m => finalPrice += parseFloat(m.price) || 0);

                    const cartKey = `${this.selectedItem.id}-${this.activeVariant?.id || '0'}-${this.activeModifiers.map(m => m.id).sort().join(',')}-${this.itemNote}`;

                    const existing = this.cart.find(i => i.cartKey === cartKey);
                    if (existing) {
                        existing.qty += this.modalQty;
                    } else {
                        this.cart.push({
                            cartKey: cartKey,
                            id: this.selectedItem.id,
                            name: this.getLocName(this.selectedItem),
                            image: this.selectedItem.image,
                            image_url: this.selectedItem.image_url || '',
                            basePrice: parseFloat(this.selectedItem.price) || 0,
                            price: finalPrice,
                            qty: this.modalQty,
                            variant: this.activeVariant ? { id: this.activeVariant.id, name: this.getLocName(this.activeVariant) } : null,
                            modifiers: this.activeModifiers.map(m => ({ id: m.id, name: this.getLocName(m) })),
                            note: this.itemNote
                        });
                    }
                    this.saveCart();
                    this.showModal = false;
                    // Don't auto-open cart - let user continue shopping
                    // Show a quick toast confirmation instead
                    this.showAddedToast();
                },

                showAddedToast() {
                    // Create and show a quick toast
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-full text-sm font-bold z-[200] shadow-2xl';
                    toast.innerHTML = '✓ {{ __('Added to cart') }}';
                    document.body.appendChild(toast);
                    setTimeout(() => toast.remove(), 1500);
                },

                updateCartQty(index, delta) {
                    this.cart[index].qty += delta;
                    if (this.cart[index].qty < 1) {
                        this.removeFromCart(index);
                    } else {
                        this.saveCart();
                    }
                },

                removeFromCart(index) {
                    if (confirm('{{ __('Remove this item from your order?') }}')) {
                        this.cart.splice(index, 1);
                        this.saveCart();
                    }
                },

                calculateCartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                saveCart() {
                    localStorage.setItem('cart_' + this.tenantSlug, JSON.stringify(this.cart));
                    this.syncGlobalCart();
                },

                syncGlobalCart() {
                    const count = this.cart.reduce((sum, i) => sum + i.qty, 0);
                    window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: count } }));
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