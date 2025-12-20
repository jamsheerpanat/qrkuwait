<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KDS - {{ $tenant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=Tajawal:wght@400;700;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'Tajawal', 'sans-serif'],
                    },
                    colors: {
                        brand: { 500: '#5c7bff', 600: '#3d56ff' }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
    
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-slate-950 text-white overflow-hidden h-screen font-sans" x-data="kdsBoard()" x-init="init()"
    :dir="isArabic ? 'rtl' : 'ltr'">
    <!-- Header -->
    <header
        class="h-20 bg-slate-900 border-b border-white/5 flex items-center justify-between px-10 shadow-2xl z-50 relative">
        <div class="flex items-center gap-6">
            <h1 class="text-2xl font-black italic tracking-tighter">
                <span x-text="isArabic ? 'لوحة' : 'KITCHEN'"></span><span class="text-brand-500"
                    x-text="isArabic ? ' المطبخ' : 'DISPLAY'"></span>
            </h1>
            <div class="h-8 w-px bg-white/10"></div>
            <span class="text-slate-500 font-bold uppercase tracking-widest text-xs">{{ $tenant->name }}</span>
        </div>
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3 glass bg-white/5 px-4 py-2 rounded-2xl border border-white/10">
                <span class="w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(239,68,68,0.5)]"></span>
                <span class="text-[10px] font-black tracking-widest text-slate-300"
                    x-text="isArabic ? 'مباشر' : 'LIVE FEED'"></span>
            </div>
<div class="flex items-center gap-2">
    <button @click="isArabic = !isArabic"
        class="w-12 h-12 flex items-center justify-center bg-slate-800 rounded-2xl font-black text-xs hover:bg-slate-700 transition">
        <span x-text="isArabic ? 'EN' : 'AR'"></span>
    </button>
                <button @click="toggleFullscreen()"
                    class="w-12 h-12 flex items-center justify-center bg-slate-800 rounded-2xl text-slate-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                    </svg>
                </button>
                <button @click="soundEnabled = !soundEnabled"
                    class="w-12 h-12 flex items-center justify-center rounded-2xl transition shadow-xl"
                    :class="soundEnabled ? 'bg-brand-600 text-white' : 'bg-slate-700 text-slate-500'">
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
                </div>
                <div class="text-3xl font-black italic tracking-tighter text-white" x-text="currentTime"></div>
        </div>
    </header>

    <!-- Board -->
    <main class="h-[calc(100vh-5rem)] p-10 overflow-x-auto no-scrollbar bg-slate-950">
        <div class="flex gap-10 h-full min-w-max">
            <!-- NEW ORDERS -->
            <div class="w-[28rem] flex flex-col gap-8 h-full">
                <div class="flex items-center justify-between border-b-4 border-orange-500/20 pb-5">
                    <h2 class="text-2xl font-black italic text-orange-500 uppercase tracking-tighter"
                        x-text="isArabic ? 'طلبات جديدة' : 'New Orders'"></h2>
                    <span
                        class="bg-orange-500 text-white w-10 h-10 flex items-center justify-center rounded-2xl text-xl font-black shadow-lg shadow-orange-500/20"
                        x-text="ordersByStatus('confirmed').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-6 no-scrollbar pb-20">
                    <template x-for="order in ordersByStatus('confirmed')" :key="order.id">
                        <div
                            class="bg-slate-900 border-l-[10px] border-orange-500 p-8 rounded-[2.5rem] shadow-2xl hover:scale-[1.02] transition-transform duration-300 relative overflow-hidden ring-1 ring-white/5">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-4xl font-black italic text-white tracking-tighter" x-text="'#' + order.order_no"></span>
                                    <template x-if="order.table_number">
                                        <div class="mt-2 inline-flex items-center gap-2 bg-amber-500/20 text-amber-400 px-3 py-1 rounded-xl">
                                            <span class="text-2xl">🍽️</span>
                                            <span class="font-black text-lg" x-text="'Table ' + order.table_number"></span>
                                        </div>
                                    </template>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                    <span class="px-3 py-1 bg-white/10 rounded-xl text-[10px] font-black tracking-[0.2em] text-orange-500 uppercase"
                                        x-text="order.table_number ? 'DINE-IN' : order.type"></span>
                                    <template x-if="order.source === 'waiter'">
                                        <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-lg text-[10px] font-black">WAITER</span>
                                    </template>
                                </div>
                            </div>
                            <div class="space-y-4 mb-8">
                                <template x-for="item in order.items">
                                    <div class="space-y-1">
                                        <div class="flex justify-between items-baseline text-xl font-black">
                                            <span x-text="item.name" class="text-white"></span>
                                            <span class="text-orange-500 ml-4 font-black text-2xl" x-text="'x' + item.qty"></span>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 uppercase flex flex-col gap-1 pl-4"
                                            x-show="item.variants || item.modifiers || item.notes">
                                            <template x-if="item.variants">
                                                <span class="text-slate-400" x-text="'• ' + (item.variants.name || item.variants)"></span>
                                            </template>
                                            <template x-for="m in item.modifiers">
                                                <span class="text-slate-400" x-text="'• ' + (m.name || m)"></span>
                                            </template>
                                            <template x-if="item.notes">
                                                <span class="text-yellow-500 italic bg-yellow-500/10 px-2 py-1 rounded" x-text="'💬 ' + item.notes"></span>
                                            </template>
                                        </div>
                                    </div>
                                    </template>
                                    <!-- Global Note -->
                                    <template x-if="order.notes">
                                        <div class="bg-blue-500/10 p-4 rounded-xl border border-blue-500/20 mt-4">
                                            <div class="text-[10px] font-black text-blue-400 uppercase tracking-widest mb-1"
                                                x-text="isArabic ? 'ملاحظة الطلب' : 'ORDER NOTE'"></div>
                                            <div class="text-sm font-bold text-blue-100 italic" x-text="order.notes"></div>
                                    </div>
                                </template>
                            </div>
<div class="flex items-center justify-between pt-6 border-t border-white/5">
    <div class="flex flex-col">
        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"
            x-text="isArabic ? 'الوقت المنقضي' : 'ELAPSED TIME'"></span>
        <span class="text-lg font-black italic tracking-tighter"
            :class="order.elapsed > 15 ? 'text-red-500 animate-pulse' : 'text-slate-400'"
            x-text="order.elapsed + ' MINS'"></span>
    </div>
                                <button @click="updateStatus(order.id, 'preparing')"
                                    class="bg-orange-500 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 shadow-xl shadow-orange-500/10 transition-all active:scale-95" x-text="isArabic ? 'ابدأ' : 'START'"></button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- PREPARING -->
            <div class="w-[28rem] flex flex-col gap-8 h-full">
                <div class="flex items-center justify-between border-b-4 border-brand-500/20 pb-5">
                    <h2 class="text-2xl font-black italic text-brand-500 uppercase tracking-tighter"
                        x-text="isArabic ? 'قيد التحضير' : 'Preparing'"></h2>
                    <span
                        class="bg-brand-500 text-white w-10 h-10 flex items-center justify-center rounded-2xl text-xl font-black shadow-lg shadow-brand-500/20"
                        x-text="ordersByStatus('preparing').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-6 no-scrollbar pb-20">
                    <template x-for="order in ordersByStatus('preparing')" :key="order.id">
                        <div
                            class="bg-slate-900 border-l-[10px] border-brand-500 p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden ring-1 ring-white/5">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <span class="text-4xl font-black italic text-white tracking-tighter" x-text="'#' + order.order_no"></span>
                                    <template x-if="order.table_number">
                                        <div class="mt-2 inline-flex items-center gap-2 bg-amber-500/20 text-amber-400 px-3 py-1 rounded-xl">
                                            <span class="text-2xl">🍽️</span>
                                            <span class="font-black text-lg" x-text="'Table ' + order.table_number"></span>
                                        </div>
                                    </template>
                                    </div>
                                <span
                                    class="px-3 py-1 bg-brand-500/20 rounded-xl text-[10px] font-black tracking-[0.2em] text-brand-500 uppercase"
                                    x-text="order.table_number ? 'DINE-IN' : order.type"></span>
                            </div>
                        
                            <div class="space-y-4 mb-8">
                                <template x-for="item in order.items">
                                    <div class="space-y-1">
                                        <div class="flex justify-between items-baseline text-xl font-black">
                                            <span x-text="item.name" class="text-white"></span>
                                            <span class="text-brand-500 ml-4 font-black text-2xl" x-text="'x' + item.qty"></span>
                                        </div>
                                        <div class="text-xs font-bold text-slate-500 uppercase flex flex-col gap-1 pl-4"
                                            x-show="item.variants || item.modifiers || item.notes">
                                            <template x-if="item.variants">
                                                <span class="text-slate-400" x-text="'• ' + (item.variants.name || item.variants)"></span>
                                            </template>
                                            <template x-for="m in item.modifiers">
                                                <span class="text-slate-400" x-text="'• ' + (m.name || m)"></span>
                                            </template>
                                            <template x-if="item.notes">
                                                <span class="text-yellow-500 italic bg-yellow-500/10 px-2 py-1 rounded" x-text="'💬 ' + item.notes"></span>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
<div class="flex items-center justify-between pt-6 border-t border-white/5">
    <div class="flex flex-col">
        <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest"
            x-text="isArabic ? 'الوقت المنقضي' : 'ELAPSED TIME'"></span>
        <span class="text-lg font-black italic tracking-tighter text-slate-400" x-text="order.elapsed + ' MINS'"></span>
    </div>
                                <button @click="updateStatus(order.id, 'ready')"
                                    class="bg-brand-600 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-700 shadow-xl shadow-brand-500/10 transition-all active:scale-95" x-text="isArabic ? 'جاهز' : 'READY'"></button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- READY -->
            <div class="w-[28rem] flex flex-col gap-8 h-full">
                <div class="flex items-center justify-between border-b-4 border-green-500/20 pb-5">
                    <h2 class="text-2xl font-black italic text-green-500 uppercase tracking-tighter"
                        x-text="isArabic ? 'جاهز للتسليم' : 'Ready'"></h2>
                    <span
                        class="bg-green-500 text-white w-10 h-10 flex items-center justify-center rounded-2xl text-xl font-black shadow-lg shadow-green-500/20"
                        x-text="ordersByStatus('ready').length"></span>
                </div>
                <div class="flex-1 overflow-y-auto space-y-6 no-scrollbar pb-20 opacity-50">
                    <template x-for="order in ordersByStatus('ready')" :key="order.id">
                        <div class="bg-slate-900 border-l-[10px] border-green-500 p-8 rounded-[2.5rem] shadow-xl relative overflow-hidden">
                            <div class="flex justify-between items-center">
                                <span class="text-4xl font-black italic text-white tracking-tighter" x-text="'#' + order.order_no"></span>
                                <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center text-green-500">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4 text-[10px] font-black text-slate-500 uppercase tracking-widest"
                                x-text="isArabic ? 'في انتظار العميل' : 'Waiting Collection'"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>

    <audio id="alertSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <script>
        function kdsBoard() {
            return {
                orders: [],
                currentTime: '',
                soundEnabled: true,
                isArabic: false,
                lastOrderCount: 0,

                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    this.refreshFeed();
                    setInterval(() => this.refreshFeed(), 5000);
                },

                updateTime() {
                    const now = new Date();
                    this.currentTime = now.toLocaleTimeString(this.isArabic ? 'ar-KW' : 'en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
                },

                toggleFullscreen() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen();
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        }
                    }
                },

                async refreshFeed() {
                    try {
                        const response = await fetch('{{ route("admin.kds.feed") }}');
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