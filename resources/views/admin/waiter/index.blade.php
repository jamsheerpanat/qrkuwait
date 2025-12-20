<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Waiter Order - {{ $tenant->name }} | QRKuwait</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/qrkuwait-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --card-hover: #334155;
            --primary: #6366f1;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body x-data="waiterApp()" x-init="init()">
    <!-- Header -->
    <header class="h-16 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-xl font-bold">🍽️ Waiter Order</h1>
            <span class="text-slate-500">|</span>
            <span class="text-slate-400 text-sm">{{ $tenant->name }}</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-sm text-slate-400" x-text="currentTime"></div>
        </div>
    </header>

    <main class="h-[calc(100vh-4rem)] flex">
        <!-- Left: Table Selection & Menu -->
        <div class="flex-1 flex flex-col border-r border-slate-700">
            <!-- Table Selection -->
            <div class="p-4 bg-slate-800 border-b border-slate-700">
                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 block">Select Table</label>
                <div class="flex gap-2 flex-wrap">
                    <template x-for="t in 20" :key="t">
                        <button @click="selectTable(t)"
                            class="w-12 h-12 rounded-xl font-bold text-sm transition-all flex items-center justify-center"
                            :class="selectedTable === t ? 'bg-amber-500 text-white scale-110 shadow-lg' : 'bg-slate-700 text-slate-300 hover:bg-slate-600'"
                            x-text="t">
                        </button>
                    </template>
                </div>
            </div>

            <!-- Categories & Items -->
            <div class="flex-1 flex overflow-hidden">
                <!-- Categories -->
                <div class="w-32 bg-slate-800 border-r border-slate-700 overflow-y-auto no-scrollbar">
                    <button @click="selectedCategory = 'all'"
                        class="w-full p-4 text-left text-xs font-bold uppercase tracking-wider transition-all"
                        :class="selectedCategory === 'all' ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-700'">
                        All Items
                    </button>
                    @foreach($categories as $cat)
                        <button @click="selectedCategory = {{ $cat->id }}"
                            class="w-full p-4 text-left text-xs font-bold uppercase tracking-wider transition-all"
                            :class="selectedCategory === {{ $cat->id }} ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-700'">
                            {{ $cat->name['en'] ?? '' }}
                        </button>
                    @endforeach
                </div>

                <!-- Items Grid -->
                <div class="flex-1 p-4 overflow-y-auto no-scrollbar">
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($items as $item)
                            <button 
                                x-show="selectedCategory === 'all' || selectedCategory === {{ $item->category_id }}"
                                @click="addToCart({{ $item->id }}, '{{ addslashes($item->name['en'] ?? $item->name['ar'] ?? 'Item') }}', {{ $item->price }})"
                                class="bg-slate-700 hover:bg-slate-600 rounded-xl p-4 text-left transition-all active:scale-95"
                                :class="selectedTable ? '' : 'opacity-50 pointer-events-none'">
                                <div class="font-bold text-sm truncate">{{ $item->name['en'] ?? $item->name['ar'] ?? 'Item' }}</div>
                                <div class="text-indigo-400 font-bold text-xs mt-1">{{ number_format($item->price, 3) }} KWD</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Cart -->
        <div class="w-96 flex flex-col bg-slate-800">
            <!-- Cart Header -->
            <div class="p-4 border-b border-slate-700 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-lg">Current Order</h2>
                    <template x-if="selectedTable">
                        <div class="flex items-center gap-2 text-amber-400 text-sm font-bold">
                            <span>🍽️</span>
                            <span x-text="'Table ' + selectedTable"></span>
                        </div>
                    </template>
                    <template x-if="!selectedTable">
                        <div class="text-slate-500 text-sm">Select a table first</div>
                    </template>
                </div>
                <button @click="clearCart()" class="text-xs text-slate-500 hover:text-red-400 font-bold uppercase tracking-wider">
                    Clear
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-3">
                <template x-if="cart.length === 0">
                    <div class="flex flex-col items-center justify-center h-full text-slate-500">
                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="font-bold">No items yet</p>
                        <p class="text-xs mt-1">Tap items to add</p>
                    </div>
                </template>

                <template x-for="(item, index) in cart" :key="index">
                    <div class="bg-slate-700 rounded-xl p-3 flex items-center gap-3">
                        <div class="flex-1">
                            <div class="font-bold text-sm" x-text="item.name"></div>
                            <div class="text-xs text-indigo-400" x-text="(item.price * item.qty).toFixed(3) + ' KWD'"></div>
                        </div>
                        <div class="flex items-center gap-2 bg-slate-800 rounded-lg px-2 py-1">
                            <button @click="updateQty(index, -1)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-white font-bold">−</button>
                            <span class="w-6 text-center font-bold" x-text="item.qty"></span>
                            <button @click="updateQty(index, 1)" class="w-7 h-7 flex items-center justify-center text-slate-400 hover:text-white font-bold">+</button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Notes -->
            <div class="p-4 border-t border-slate-700">
                <input type="text" x-model="orderNotes" placeholder="Order notes (optional)"
                    class="w-full bg-slate-700 border-none rounded-xl p-3 text-sm placeholder-slate-500 focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Cart Footer -->
            <div class="p-4 bg-slate-900 border-t border-slate-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-400 text-sm font-bold uppercase">Total</span>
                    <span class="text-2xl font-black" x-text="cartTotal.toFixed(3) + ' KWD'"></span>
                </div>
                <button @click="sendOrder()"
                    :disabled="cart.length === 0 || !selectedTable || sending"
                    class="w-full bg-green-600 hover:bg-green-700 disabled:bg-slate-700 disabled:text-slate-500 text-white font-bold py-4 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-3">
                    <template x-if="!sending">
                        <span class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send to Kitchen
                        </span>
                    </template>
                    <template x-if="sending">
                        <span>Sending...</span>
                    </template>
                </button>
            </div>
        </div>
    </main>

    <!-- Toast -->
    <div x-show="toast.show" x-transition
        class="fixed bottom-6 left-1/2 -translate-x-1/2 px-6 py-4 rounded-2xl font-bold shadow-2xl z-50"
        :class="toast.type === 'success' ? 'bg-green-600 text-white' : 'bg-red-600 text-white'">
        <span x-text="toast.message"></span>
    </div>

    <script>
        function waiterApp() {
            return {
                selectedTable: null,
                selectedCategory: 'all',
                cart: [],
                orderNotes: '',
                sending: false,
                currentTime: '',
                toast: { show: false, message: '', type: 'success' },

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },

                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                },

                selectTable(t) {
                    this.selectedTable = t;
                },

                addToCart(id, name, price) {
                    if (!this.selectedTable) {
                        this.showToast('Please select a table first', 'error');
                        return;
                    }
                    
                    const existing = this.cart.find(i => i.id === id);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({ id, name, price, qty: 1 });
                    }
                },

                updateQty(index, delta) {
                    this.cart[index].qty += delta;
                    if (this.cart[index].qty <= 0) {
                        this.cart.splice(index, 1);
                    }
                },

                clearCart() {
                    this.cart = [];
                    this.orderNotes = '';
                },

                get cartTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                async sendOrder() {
                    if (this.cart.length === 0 || !this.selectedTable) return;
                    
                    this.sending = true;
                    
                    try {
                        const response = await fetch('{{ route("admin.waiter.order") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                table_number: String(this.selectedTable),
                                items: this.cart,
                                notes: this.orderNotes
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.showToast(data.message, 'success');
                            this.clearCart();
                            // Keep same table selected for easy re-order
                        } else {
                            this.showToast('Failed to send order', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        this.showToast('Error sending order', 'error');
                    }
                    
                    this.sending = false;
                },

                showToast(message, type = 'success') {
                    this.toast = { show: true, message, type };
                    setTimeout(() => this.toast.show = false, 3000);
                }
            }
        }
    </script>
</body>
</html>
