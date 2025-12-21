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
                            class="w-16 h-16 rounded-2xl font-black text-sm transition-all flex flex-col items-center justify-center gap-1 shadow-sm relative overflow-hidden"
                            :class="selectedTable === t 
                                ? 'bg-indigo-600 text-white shadow-indigo-200' 
                                : (isTableActive(t) 
                                    ? 'bg-amber-50 text-amber-700 border-2 border-amber-200 shadow-amber-50' 
                                    : 'bg-white text-slate-400 border border-slate-200 hover:border-indigo-400 hover:bg-slate-50')">
                            <span x-text="t"></span>
                            <template x-if="isTableActive(t)">
                                <span class="text-[8px] uppercase tracking-tighter opacity-70">Serving</span>
                            </template>
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
                            {{ $cat->getLocalizedName() }}
                        </button>
                    @endforeach
                </div>

                <!-- Items Grid -->
                <div class="flex-1 p-4 overflow-y-auto no-scrollbar">
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($items as $item)
                            <button 
                                x-show="selectedCategory === 'all' || selectedCategory === {{ $item->category_id }}"
                                @click="addToCart({{ $item->id }}, '{{ addslashes($item->getLocalizedName()) }}', {{ $item->price }})"
                                class="bg-slate-700 hover:bg-slate-600 rounded-xl p-4 text-left transition-all active:scale-95"
                                :class="selectedTable ? '' : 'opacity-50 pointer-events-none'">
                                <div class="font-bold text-sm truncate">{{ $item->getLocalizedName() }}</div>
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
            <div class="flex-1 overflow-y-auto no-scrollbar p-4 space-y-6">
                <!-- Existing Items -->
                <template x-if="existingItems.length > 0">
                    <div>
                        <div
                            class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-500 mb-3 px-2">
                            <span>Serving Now</span>
                            <div class="h-px flex-1 bg-slate-700/50"></div>
                        </div>
                        <div class="space-y-2">
                            <template x-for="item in existingItems" :key="item.id">
                                <div
                                    class="bg-slate-800/50 border border-slate-700/50 rounded-xl p-3 flex items-center gap-3 opacity-60">
                                    <div class="flex-1">
                                        <div class="font-bold text-sm" x-text="item.item_name"></div>
                                        <div class="text-[10px] text-slate-500"
                                            x-text="parseFloat(item.price).toFixed(3) + ' x ' + parseInt(item.qty)"></div>
                                    </div>
                                    <div class="text-xs font-bold text-indigo-400" x-text="parseFloat(item.line_total).toFixed(3)">
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            
                <!-- New Items -->
                <div>
                    <template x-if="cart.length === 0">
                        <div class="flex flex-col items-center justify-center py-10 text-slate-500">
                            <svg class="w-12 h-12 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <p class="font-bold text-xs uppercase tracking-widest opacity-50">Add items to order</p>
                            </div>
                            </template>

                    <template x-if="cart.length > 0">
                        <div>
                            <div class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-400 mb-3 px-2">
                                <span>New Items</span>
                                <div class="h-px flex-1 bg-indigo-500/20"></div>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(item, index) in cart" :key="index">
                                    <div class="bg-indigo-600/10 border border-indigo-500/20 rounded-xl p-3 flex items-center gap-3">
                                        <div class="flex-1">
                                            <div class="font-bold text-sm" x-text="item.name"></div>
                                            <div class="text-xs text-indigo-400" x-text="(item.price * item.qty).toFixed(3) + ' KWD'"></div>
                                        </div>
                                        <div class="flex items-center gap-2 bg-indigo-600/20 rounded-lg px-2 py-1">
                                            <button @click="updateQty(index, -1)"
                                                class="w-7 h-7 flex items-center justify-center text-indigo-300 hover:text-white font-bold">−</button>
                                            <span class="w-6 text-center font-bold text-indigo-400" x-text="item.qty"></span>
                                            <button @click="updateQty(index, 1)"
                                                class="w-7 h-7 flex items-center justify-center text-indigo-300 hover:text-white font-bold">+</button>
                                        </div>
                                        </div>
                                        </template>
                                        </div>
                            </div>
                            </template>
                            </div>
                            </div>

            <!-- Notes -->
            <div class="p-4 border-t border-slate-700">
                <input type="text" x-model="orderNotes" placeholder="Order notes (optional)"
                    class="w-full bg-slate-700 border-none rounded-xl p-3 text-sm placeholder-slate-500 focus:ring-2 focus:ring-indigo-500">
            </div>

            <!-- Cart Footer -->
            <div class="p-4 bg-slate-900 border-t border-slate-700">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-slate-400 text-sm font-bold uppercase" x-text="cart.length > 0 ? 'New Total' : 'Order Total'"></span>
                    <span class="text-2xl font-black"
                        x-text="(cartTotal + (existingItems.reduce((sum, i) => sum + parseFloat(i.line_total), 0))).toFixed(3) + ' KWD'"></span>
                </div>
                <div class="space-y-3">
                    <button @click="sendOrder()"
                        x-show="cart.length > 0"
                        :disabled="!selectedTable || sending"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-700 disabled:text-slate-500 text-white font-black py-4 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-3">
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
<button @click="checkoutTable()" x-show="cart.length === 0 && activeOrderNo"
    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-black py-4 rounded-xl transition-all active:scale-95 flex items-center justify-center gap-3">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Checkout & Print Bill
</button>
</div>
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
                existingItems: [],
                activeOrders: @json($activeOrders),
                orderNotes: '',
                sending: false,
                currentTime: '',
                toast: { show: false, message: '', type: 'success' },
                activeOrderNo: null,

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                },

                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                },

                isTableActive(t) {
                    return this.activeOrders.some(o => o.table_number == t);
                },

                async selectTable(t) {
                    this.selectedTable = t;
                    this.cart = [];
                    this.existingItems = [];
                    this.activeOrderNo = null;

                    const active = this.activeOrders.find(o => o.table_number == t);
                    if (active) {
                        this.activeOrderNo = active.order_no;
                        // Fetch existing items for this table
                        try {
                            const response = await fetch(`{{ route('admin.waiter.table', '') }}/${t}`);
                            const orders = await response.json();
                            if (orders.length > 0) {
                                // Merge all items from active orders for this table
                                this.existingItems = orders.flatMap(o => o.items);
                            }
                        } catch (e) {
                            console.error('Error fetching table items', e);
                        }
                    }
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
                            // Refresh page or at least refresh tables to show serving state
                            window.location.reload(); 
                        } else {
                            this.showToast('Failed to send order', 'error');
                        }
                    } catch (error) {
                        console.error(error);
                        this.showToast('Error sending order', 'error');
                    }
                    
                    this.sending = false;
                },

                async checkoutTable() {
                    if (!this.activeOrderNo) return;
                    if (!confirm('Are you sure you want to checkout Table ' + this.selectedTable + '?')) return;

                    try {
                        const response = await fetch(`{{ route('admin.waiter.checkout', '') }}/${this.activeOrderNo}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        if (data.success) {
                            this.showToast(data.message, 'success');
                            // Open print view in new tab
                            window.open(`{{ route('admin.orders.index') }}/${data.order_id}/print`, '_blank');
                            window.location.reload();
                        }
                    } catch (e) {
                        this.showToast('Error during checkout', 'error');
                    }
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
