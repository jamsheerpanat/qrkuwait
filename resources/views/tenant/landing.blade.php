@extends('layouts.tenant')

@section('content')
    <div x-data="menuState()" x-init="init()" x-cloak 
        @open-cart.window="showCart = true"
        @open-search.window="showSearch = true"
        class="pb-32 relative">

        <!-- Search Drawer / Modal -->
        <div x-show="showSearch" class="fixed inset-0 z-[100] flex items-start justify-center pt-24 px-4" x-cloak @keydown.escape.window="showSearch = false">
            <div @click="showSearch = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            <div class="relative bg-white w-full max-w-2xl rounded-3xl overflow-hidden shadow-2xl p-6"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-8"
                x-transition:enter-end="opacity-100 translate-y-0">
                <div class="relative flex items-center">
                    <svg class="absolute left-5 w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="searchQuery" x-ref="searchInput" @input="showSearch = true"
                        class="w-full bg-slate-100 border-none rounded-2xl py-5 pl-14 pr-6 text-lg font-bold focus:ring-2 focus:ring-brand-600 transition"
                        placeholder="{{ __('Search dishes, drinks or categories...') }}" autofocus>
                    <button @click="showSearch = false" class="absolute right-5 text-slate-400 font-bold hover:text-slate-900">{{ __('Close') }}</button>
                </div>

                <div class="mt-8 max-h-[60vh] overflow-y-auto no-scrollbar space-y-4">
                    <template x-if="filteredItems.length === 0">
                        <div class="text-center py-12 text-slate-400 italic">{{ __('No items found matching your search...') }}</div>
                    </template>
                    <template x-for="item in filteredItems" :key="item.id">
                        <div @click="openItem(item); showSearch = false" class="flex gap-4 p-4 rounded-2xl hover:bg-slate-50 transition cursor-pointer group">
                            <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden flex-shrink-0">
                                <img :src="item.image_url" class="w-full h-full object-cover" x-show="item.image_url">
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-slate-900" x-text="getLocName(item)"></div>
                                <div class="text-xs text-slate-500 line-clamp-1" x-text="getLocDesc(item)"></div>
                                <div class="text-sm font-black text-brand-600 mt-1 italic" x-text="formatPrice(item.price)"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <!-- Hero / Banner -->
        <div class="relative h-64 md:h-96 rounded-[3.5rem] overflow-hidden mb-16 shadow-2xl group border-4 border-white">
            @if($tenant->cover_url)
                <img src="{{ $tenant->cover_url }}"
                        class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-gradient-to-br from-slate-900 to-brand-900 flex items-center justify-center">
                    <h2 class="text-5xl font-extrabold text-white italic opacity-10 tracking-widest">QR<span class="text-brand-500">KUWAIT</span></h2>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-transparent"></div>

            <div class="absolute top-10 right-10 flex gap-3">
                 <div class="glass px-4 py-2 rounded-2xl flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse shadow-[0_0_8px_rgba(34,197,94,0.6)]"></span>
                    <span class="text-[10px] font-black uppercase text-slate-900 tracking-wider">{{ __('Serving Live') }}</span>
                 </div>
            </div>

            <div class="absolute bottom-10 left-10 md:left-14 flex items-end gap-6 text-white w-full pr-20">
                <div class="w-24 h-24 md:w-36 md:h-36 bg-white rounded-[2.5rem] p-3 shadow-2xl border-4 border-white/20 overflow-hidden transition-transform duration-500 hover:rotate-2">
                    @if($tenant->logo_url)
                        <img src="{{ $tenant->logo_url }}" class="w-full h-full object-contain rounded-2xl">
                    @else
                        <div class="w-full h-full bg-brand-600 rounded-2xl flex items-center justify-center text-5xl font-black italic">
                            {{ substr($tenant->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="mb-4">
                    <h1 class="text-4xl md:text-7xl font-black italic tracking-tighter drop-shadow-lg">{{ $tenant->name }}</h1>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-3 py-1 bg-brand-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $tenant->type }}</span>
                        <span class="text-white/80 font-bold text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"/></svg>
                            Kuwait City
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sticky Header Extensions (Search & Categories) -->
        <div class="sticky top-20 z-40 space-y-4 pt-4 mb-12 -mx-4 px-4 overflow-hidden">
            <!-- Search Quick Bar -->
            <div @click="showSearch = true" class="glass rounded-3xl p-4 flex items-center gap-4 cursor-pointer shadow-lg shadow-slate-200/50 hover:bg-white transition group">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center group-hover:bg-brand-50 transition">
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-brand-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <span class="text-slate-400 font-bold text-sm">{{ __('Search for items, categories or ingredients...') }}</span>
            </div>

            <!-- Categories Scroll -->
            <div class="flex gap-3 overflow-x-auto no-scrollbar scroll-smooth">
                <button @click="activeCat = 'all'"
                    :class="activeCat === 'all' ? 'bg-slate-900 text-white scale-105 shadow-xl shadow-slate-200' : 'bg-white text-slate-500 shadow-sm'"
                    class="px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all duration-500 border border-slate-100/50">
                    {{ __('Everything') }}
                </button>
                @foreach($categories as $category)
                    <button @click="activeCat = {{ $category->id }}"
                        :class="activeCat === {{ $category->id }} ? 'bg-brand-600 text-white scale-105 shadow-xl shadow-brand-100' : 'bg-white text-slate-500 shadow-sm'"
                        class="px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest transition-all duration-500 border border-slate-100/50 whitespace-nowrap">
                        {{ $category->getLocalizedName() }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($categories as $category)
                @foreach($category->items as $item)
                    <div x-show="activeCat === 'all' || activeCat === {{ $category->id }}"
                        x-transition:enter="transition ease-out duration-400" x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="bg-white rounded-[3rem] p-8 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] border border-slate-100 hover:shadow-2xl hover:shadow-slate-200/60 transition-all duration-500 group relative @if(!$item->is_active) opacity-50 grayscale @endif"
                        @click="@if($item->is_active) openItem({{ $item->toJson() }}) @endif">

                        <div class="flex gap-6 h-full items-center">
                            <div class="flex-1 space-y-4">
                                <div class="space-y-1">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-2xl font-black text-slate-900 group-hover:text-brand-600 transition leading-tight">
                                            {{ $item->getLocalizedName() }}
                                        </h3>
                                    </div>
                                    <p class="text-slate-400 text-sm line-clamp-2 font-medium leading-relaxed">
                                        {{ $item->getLocalizedDescription() }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-black text-slate-900 italic tracking-tighter">
                                        {{ number_format($item->price, 3) }}
                                        <span class="text-[10px] font-black text-slate-400 not-italic uppercase ml-0.5">{{ $settings['currency'] ?? 'KWD' }}</span>
                                    </span>
                                    @if($item->is_active)
                                        <button class="w-12 h-12 bg-slate-50 text-slate-900 rounded-2xl flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white transition-all duration-500 shadow-sm border border-slate-100/50">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    @else
                                        <span class="text-[10px] font-black uppercase text-red-500 bg-red-50 px-2 py-1 rounded-lg">{{ __('Sold Out') }}</span>
                                    @endif
                                </div>
                            </div>
                            @if($item->image)
                                <div class="w-28 h-28 md:w-32 md:h-32 rounded-[2.5rem] overflow-hidden shadow-inner flex-shrink-0 border-2 border-slate-50">
                                    <img src="{{ $item->image_url }}"
                                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <!-- Item Picker Modal (Premium Sidebar Flow) -->
        <div x-show="showModal" class="fixed inset-0 z-[100] flex items-end sm:items-center justify-end" x-cloak @keydown.escape.window="showModal = false">
            <div @click="showModal = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity duration-500" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"></div>
            <div class="relative bg-white w-full max-w-xl h-full sm:h-[94vh] sm:mr-4 sm:rounded-[3.5rem] overflow-hidden shadow-2xl flex flex-col"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0">

                <template x-if="selectedItem">
                    <div class="flex-1 flex flex-col overflow-y-auto no-scrollbar">
                        <!-- Modal Image -->
                        <div class="relative h-64 flex-shrink-0 bg-slate-100">
                            <img :src="selectedItem.image_url" class="w-full h-full object-cover" x-show="selectedItem.image_url">
                            <div x-show="!selectedItem.image" class="w-full h-full flex items-center justify-center bg-brand-50">
                                <span class="text-4xl text-brand-200 font-black italic">QRKUWAIT</span>
                            </div>
                            <button @click="showModal = false" class="absolute top-6 right-6 w-12 h-12 glass rounded-2xl flex items-center justify-center text-slate-900 shadow-xl border border-white/50">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="p-10 space-y-10">
                            <div>
                                <h2 class="text-4xl font-black italic text-slate-900 tracking-tighter" x-text="getLocName(selectedItem)"></h2>
                                <p class="text-slate-400 mt-3 text-lg font-medium leading-relaxed" x-text="getLocDesc(selectedItem)"></p>
                            </div>

                            <!-- Variants -->
                            <template x-if="selectedItem.variants && selectedItem.variants.length">
                                <div class="space-y-6">
                                    <div class="flex justify-between items-center">
                                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">{{ __('Choose Option') }}</h4>
                                        <span class="px-2 py-1 bg-brand-50 text-brand-600 text-[10px] font-black rounded-lg">{{ __('Required') }}</span>
                                    </div>
                                    <div class="grid grid-cols-1 gap-3">
                                        <template x-for="v in selectedItem.variants" :key="v.id">
                                            <div @click="activeVariant = v" 
                                                class="p-5 rounded-3xl border-2 transition-all duration-300 cursor-pointer flex justify-between items-center group"
                                                :class="activeVariant && activeVariant.id === v.id ? 'border-brand-600 bg-brand-50/30' : 'border-slate-50 bg-slate-50/50 hover:bg-slate-50'">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors" :class="activeVariant && activeVariant.id === v.id ? 'border-brand-600' : 'border-slate-300'">
                                                        <div class="w-3 h-3 rounded-full bg-brand-600" x-show="activeVariant && activeVariant.id === v.id"></div>
                                                    </div>
                                                    <span class="font-bold text-slate-800" x-text="getLocName(v)"></span>
                                                </div>
                                                <div class="font-black text-slate-900 italic">+<span x-text="formatPrice(v.price)"></span></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Modifiers -->
                            <template x-if="selectedItem.modifiers && selectedItem.modifiers.length">
                                <div class="space-y-6">
                                    <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">{{ __('Extra Customization') }}</h4>
                                    <div class="grid grid-cols-1 gap-3">
                                        <template x-for="m in selectedItem.modifiers" :key="m.id">
                                            <div @click="toggleModifier(m)" 
                                                class="p-5 rounded-3xl border-2 transition-all duration-300 cursor-pointer flex justify-between items-center group"
                                                :class="isModifierActive(m) ? 'border-brand-600 bg-brand-50/30' : 'border-slate-50 bg-slate-50/50 hover:bg-slate-50'">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-6 h-6 rounded-lg border-2 flex items-center justify-center transition-colors" :class="isModifierActive(m) ? 'bg-brand-600 border-brand-600' : 'border-slate-300'">
                                                        <svg x-show="isModifierActive(m)" class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    <span class="font-bold text-slate-800" x-text="getLocName(m)"></span>
                                                </div>
                                                <div class="font-black text-slate-900 italic">+<span x-text="formatPrice(m.price)"></span></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <!-- Item Notes -->
                            <div class="space-y-4">
                                <h4 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">{{ __('Any special requests?') }}</h4>
                                <textarea x-model="itemNote" class="w-full rounded-3xl border-2 border-slate-50 bg-slate-50/50 p-6 font-bold text-slate-700 focus:bg-white focus:border-brand-600 focus:ring-0 transition" placeholder="{{ __('No onions, extra sauce, etc...') }}" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="p-10 border-t border-slate-50 bg-slate-50/30 flex flex-col gap-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-6 bg-white p-3 rounded-[1.5rem] shadow-sm border border-slate-100">
                            <button @click="if(modalQty > 1) modalQty--" class="w-12 h-12 flex items-center justify-center bg-slate-50 rounded-2xl text-2xl font-black text-slate-900 hover:text-brand-600 transition">-</button>
                            <span class="text-3xl font-black w-10 text-center italic tracking-tighter" x-text="modalQty"></span>
                            <button @click="modalQty++" class="w-12 h-12 flex items-center justify-center bg-slate-50 rounded-2xl text-2xl font-black text-slate-900 hover:text-brand-600 transition">+</button>
                        </div>
                        <div class="text-right">
                             <div class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Total Price') }}</div>
                             <div class="text-4xl font-black italic tracking-tighter text-slate-900" x-text="formatPrice(calculateModalTotal())"></div>
                        </div>
                    </div>
                    <button @click="addToCart"
                        class="w-full bg-slate-900 text-white py-8 rounded-[2rem] text-xl font-black italic shadow-2xl shadow-slate-200 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                        {{ __('ADD TO MY ORDER') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Cart Sidebar / Drawer -->
        <div x-show="showCart" class="fixed inset-0 z-[110] flex items-end sm:items-center justify-end" x-cloak @keydown.escape.window="showCart = false">
            <div @click="showCart = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
            <div class="relative bg-white w-full max-w-xl h-full sm:h-[94vh] sm:mr-4 sm:rounded-[3.5rem] overflow-hidden shadow-2xl flex flex-col"
                x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0">

                <div class="p-10 flex-shrink-0 flex justify-between items-center border-b border-slate-50">
                    <div>
                        <h2 class="text-3xl font-black italic text-slate-900 tracking-tighter">{{ __('My Order') }}</h2>
                        <p class="text-[10px] font-black uppercase tracking-widest text-brand-600 mt-1" x-text="cart.length + ' {{ __('ITEMS ADDED') }}'"></p>
                    </div>
                    <button @click="showCart = false" class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 hover:text-slate-900 transition">
                         <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto no-scrollbar p-10 space-y-8">
                    <template x-if="cart.length === 0">
                        <div class="h-full flex flex-col items-center justify-center text-center space-y-6">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-200">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4l1-12z"></path></svg>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-xl font-bold text-slate-900">{{ __('Your order is empty') }}</h3>
                                <p class="text-slate-400">{{ __('Add some delicious items to get started') }}</p>
                            </div>
                            <button @click="showCart = false" class="bg-brand-600 text-white px-8 py-3 rounded-2xl font-black italic">{{ __('BROWSE MENU') }}</button>
                        </div>
                    </template>

                    <template x-for="(item, index) in cart" :key="item.cartKey">
                        <div class="flex gap-6 group">
                            <div class="w-20 h-20 bg-slate-50 rounded-2xl overflow-hidden flex-shrink-0">
                                <img :src="item.image_url" class="w-full h-full object-cover" x-show="item.image_url">
                            </div>
                            <div class="flex-1 space-y-2">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-black text-slate-900" x-text="item.name"></h4>
                                    <span class="font-black text-slate-900 italic" x-text="formatPrice(item.price * item.qty)"></span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest space-y-0.5">
                                    <template x-if="item.variant">
                                        <div x-text="'• ' + item.variant.name"></div>
                                    </template>
                                    <template x-for="m in item.modifiers" :key="m.id">
                                        <div x-text="'• ' + m.name"></div>
                                    </template>
                                    <template x-if="item.note">
                                        <div class="text-indigo-500 mt-1 italic" x-text="'💬 ' + item.note"></div>
                                    </template>
                                </div>
                                <div class="flex justify-between items-center pt-2">
                                    <div class="flex items-center gap-4 bg-slate-50 p-1.5 rounded-xl border border-slate-100">
                                        <button @click="updateCartQty(index, -1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-900 font-black">-</button>
                                        <span class="font-black text-slate-900 w-4 text-center" x-text="item.qty"></span>
                                        <button @click="updateCartQty(index, 1)" class="w-8 h-8 flex items-center justify-center bg-white rounded-lg text-slate-900 font-black">+</button>
                                    </div>
                                    <button @click="removeFromCart(index)" class="text-[10px] font-black text-red-400 uppercase tracking-widest hover:text-red-600 transition">
                                        {{ __('Remove') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="p-10 bg-slate-900 text-white rounded-t-[3.5rem] space-y-8 shadow-[0_-20px_50px_rgba(0,0,0,0.2)]">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center opacity-60">
                            <span class="font-bold text-sm uppercase tracking-widest">{{ __('Subtotal') }}</span>
                            <span class="font-black italic text-lg" x-text="formatPrice(calculateCartTotal())"></span>
                        </div>
                        <div class="flex justify-between items-center border-t border-white/10 pt-4">
                            <span class="font-black text-xl italic tracking-tighter">{{ __('Total Order') }}</span>
                            <span class="font-black text-4xl italic tracking-tighter text-brand-500" x-text="formatPrice(calculateCartTotal())"></span>
                        </div>
                    </div>
                    <a href="{{ route('tenant.checkout', $tenant->slug) }}" 
                        class="block w-full bg-brand-600 text-white py-8 rounded-[2.5rem] text-center text-xl font-black italic shadow-2xl shadow-brand-900/50 hover:scale-[1.02] active:scale-95 transition-all duration-300"
                        :class="cart.length === 0 ? 'opacity-50 pointer-events-none' : ''">
                        {{ __('PROCEED TO CHECKOUT') }}
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
                    return parseFloat(price || 0).toFixed(3);
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
                    let finalPrice = parseFloat(this.selectedItem.price);
                    if (this.activeVariant) finalPrice += parseFloat(this.activeVariant.price);
                    this.activeModifiers.forEach(m => finalPrice += parseFloat(m.price));

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
                            basePrice: parseFloat(this.selectedItem.price),
                            price: finalPrice,
                            qty: this.modalQty,
                            variant: this.activeVariant ? { id: this.activeVariant.id, name: this.getLocName(this.activeVariant) } : null,
                            modifiers: this.activeModifiers.map(m => ({ id: m.id, name: this.getLocName(m) })),
                            note: this.itemNote
                        });
                    }
                    this.saveCart();
                    this.showModal = false;
                    this.showCart = true;
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