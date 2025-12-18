<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="bg-slate-900 text-white overflow-hidden h-screen" x-data="kdsBoard()" x-init="init()">
    <!-- Header -->
    <header class="h-20 bg-slate-800 border-b border-slate-700 flex items-center justify-between px-10">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-black italic tracking-tighter uppercase">PACKING<span
                    class="text-brand-500">DISPLAY</span>
            </h1>
            <div class="h-8 w-px bg-slate-700"></div>
            <span class="text-slate-400 font-bold uppercase tracking-widest text-xs">{{ $tenant->name }}</span>
        </div>
        <div class="flex items-center gap-8">
            <div class="flex items-center gap-3">
                <span
                    class="w-3 h-3 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                <span class="text-sm font-bold text-slate-300">LIVE FEED</span>
            </div>
            <button @click="soundEnabled = !soundEnabled" class="p-3 rounded-xl transition"
                :class="soundEnabled ? 'bg-brand-600 text-white' : 'bg-slate-700 text-slate-400'">
                <svg x-show="soundEnabled" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                    </path>
                </svg>
                <svg x-show="!soundEnabled" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z">
                    </path>
                    <path d="M17 7l-10 10M7 7l10 10"></path>
                </svg>
            </button>
            <div class="text-2xl font-mono text-slate-400" x-text="currentTime"></div>
        </div>
    </header>

    <!-- Board -->
    <main class="h-[calc(100vh-5rem)] p-8 overflow-x-auto no-scrollbar">
        <div class="flex gap-8 h-full min-w-max">
            <!-- To Pick -->
            <div class="w-96 flex flex-col gap-6 h-full">
                <div class="flex items-center justify-between border-b border-orange-500/30 pb-4">
                    <h2 class="text-xl font-black italic text-orange-500 uppercase">To Pick</h2>
                    <span class="bg-orange-500 text-white px-3 py-1 rounded-lg text-sm font-black"
                        x-text="ordersByStatus('confirmed').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-4 no-scrollbar">
                    <template x-for="order in ordersByStatus('confirmed')" :key="order.id">
                        <div
                            class="bg-slate-800 border-l-8 border-orange-500 p-6 rounded-2xl shadow-xl hover:scale-[1.02] transition">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-2xl font-black italic text-white"
                                    x-text="'#' + order.order_no"></span>
                                <span class="px-2 py-1 bg-slate-700 rounded text-[10px] font-bold text-slate-400"
                                    x-text="order.type.toUpperCase()"></span>
                            </div>
                            <div class="space-y-3 mb-6">
                                <template x-for="item in order.items">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span x-text="item.name"></span>
                                        <span class="text-brand-500" x-text="'x' + item.qty"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                                <span class="text-xs font-bold text-slate-500"
                                    x-text="order.elapsed + ' MINS AGO'"></span>
                                <button @click="updateStatus(order.id, 'preparing')"
                                    class="bg-orange-500 text-white px-6 py-2 rounded-xl font-bold hover:bg-orange-600">PICK</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Picking -->
            <div class="w-96 flex flex-col gap-6 h-full">
                <div class="flex items-center justify-between border-b border-blue-500/30 pb-4">
                    <h2 class="text-xl font-black italic text-blue-500 uppercase">Picking</h2>
                    <span class="bg-blue-500 text-white px-3 py-1 rounded-lg text-sm font-black"
                        x-text="ordersByStatus('preparing').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-4 no-scrollbar">
                    <template x-for="order in ordersByStatus('preparing')" :key="order.id">
                        <div
                            class="bg-slate-800 border-l-8 border-blue-500 p-6 rounded-2xl shadow-xl relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-24 h-24 bg-blue-500/5 rotate-45 translate-x-12 -translate-y-12">
                            </div>
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-2xl font-black italic text-white"
                                    x-text="'#' + order.order_no"></span>
                                <span class="px-2 py-1 bg-blue-500/20 rounded text-[10px] font-bold text-blue-400"
                                    x-text="order.type.toUpperCase()"></span>
                            </div>
                            <div class="space-y-3 mb-6">
                                <template x-for="item in order.items">
                                    <div class="flex justify-between text-lg font-bold">
                                        <span x-text="item.name"></span>
                                        <span class="text-blue-500" x-text="'x' + item.qty"></span>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                                <span class="text-xs font-bold text-slate-500"
                                    x-text="order.elapsed + ' MINS AGO'"></span>
                                <button @click="updateStatus(order.id, 'picked')"
                                    class="bg-blue-500 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-600">PACKED</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Packed -->
            <div class="w-96 flex flex-col gap-6 h-full">
                <div class="flex items-center justify-between border-b border-green-500/30 pb-4">
                    <h2 class="text-xl font-black italic text-green-500 uppercase">Packed</h2>
                    <span class="bg-green-500 text-white px-3 py-1 rounded-lg text-sm font-black"
                        x-text="ordersByStatus('picked').length + ordersByStatus('packed').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-4 no-scrollbar opacity-60">
                    <template x-for="order in [...ordersByStatus('picked'), ...ordersByStatus('packed')]"
                        :key="order.id">
                        <div class="bg-slate-800 border-l-8 border-green-500 p-6 rounded-2xl shadow-xl">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-2xl font-black italic text-white"
                                    x-text="'#' + order.order_no"></span>
                            </div>
                            <button @click="updateStatus(order.id, 'ready')"
                                class="w-full bg-green-600 text-white py-2 rounded-xl font-bold mt-4">DISPATCH
                                READY</button>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <audio id="alertSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3"
        preload="auto"></audio>

    <script>
        function kdsBoard() {
            return {
                orders: [],
                currentTime: '',
                soundEnabled: true,
                lastOrderCount: 0,

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    this.refreshFeed();
                    setInterval(() => this.refreshFeed(), 5000);
                },

                updateTime() {
                    this.currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                },

                async refreshFeed() {
                    try {
                        const response = await fetch('{{ route("admin.kds.feed") }}?type=packing');
                        const data = await response.json();

                        if (this.soundEnabled && data.length > this.lastOrderCount) {
                            document.getElementById('alertSound').play().catch(e => console.log('Audio blocked'));
                        }

                        this.orders = data;
                        this.lastOrderCount = data.length;
                    } catch (e) {
                        console.error('Feed error', e);
                    }
                },

                ordersByStatus(status) {
                    return this.orders.filter(o => o.status === status);
                },

                async updateStatus(id, status) {
                    try {
                        await fetch(`/admin/orders/${id}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: status })
                        });
                        this.refreshFeed();
                    } catch (e) {
                        alert('Update failed');
                    }
                }
            }
        }
    </script>
</body>

</html>
```