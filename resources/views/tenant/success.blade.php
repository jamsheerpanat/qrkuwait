@extends('layouts.tenant')

@section('content')
    <div class="py-20 flex flex-col items-center text-center max-w-lg mx-auto" x-data="{ 
                imagePreview: null,
                uploading: false,
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.imagePreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }" x-init="localStorage.removeItem('cart_{{ $tenant->slug }}')">

        <div
            class="w-32 h-32 bg-green-100 text-green-600 rounded-[3rem] flex items-center justify-center mb-10 animate-bounce">
            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-4xl font-black italic text-slate-900 mb-4">Order Received!</h1>
        <p class="text-slate-500 font-bold mb-10 leading-relaxed text-lg">Your order <span
                class="text-brand-600">#{{ $order->order_no }}</span> has been successfully placed.
            @if($order->payment_method === 'knet')
                Please complete the payment and upload confirmation.
            @else
                Please send it to WhatsApp to confirm.
            @endif
        </p>

        @if(session('success'))
            <div class="w-full bg-green-50 border-2 border-green-200 rounded-2xl p-4 mb-6 text-green-900 font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="w-full bg-red-50 border-2 border-red-200 rounded-2xl p-4 mb-6 text-red-900 font-bold text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="w-full space-y-6">
            @if($order->payment_method === 'knet')
                <!-- KNET Payment Instructions -->
                <div class="bg-blue-50 border-2 border-blue-200 rounded-[2.5rem] p-8 text-left space-y-4">
                    <h3 class="text-xl font-bold text-blue-900 flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        KNET Payment Instructions
                    </h3>
                    <ol class="space-y-2 text-sm font-bold text-blue-900">
                        <li class="flex gap-2">
                            <span class="flex-shrink-0">1.</span>
                            <span>Transfer {{ number_format($order->total, 3) }} KWD to our KNET account</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="flex-shrink-0">2.</span>
                            <span>Take a screenshot of the payment confirmation</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="flex-shrink-0">3.</span>
                            <span>Upload the screenshot below or send via WhatsApp</span>
                        </li>
                    </ol>
                </div>

                <!-- Upload Screenshot Section -->
                @if(!$order->payment_screenshot)
                    <div class="bg-white border-2 border-slate-200 rounded-[2.5rem] p-8 text-left space-y-4">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Upload Payment Screenshot
                        </h3>

                        <form action="{{ route('tenant.order.upload-payment', [$tenant->slug, $order->order_no]) }}" method="POST"
                            enctype="multipart/form-data" @submit="uploading = true">
                            @csrf

                            <div class="space-y-4">
                                <label class="block">
                                    <input type="file" name="payment_screenshot" accept="image/*" @change="handleFileSelect($event)"
                                        class="hidden" id="payment-upload" required>

                                    <div @click="document.getElementById('payment-upload').click()"
                                        class="border-2 border-dashed border-slate-300 rounded-2xl p-8 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                                        <template x-if="!imagePreview">
                                            <div>
                                                <svg class="w-12 h-12 mx-auto mb-3 text-slate-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                    </path>
                                                </svg>
                                                <p class="text-sm font-bold text-slate-600">Click to upload or drag and drop</p>
                                                <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 5MB</p>
                                            </div>
                                        </template>

                                        <template x-if="imagePreview">
                                            <div>
                                                <img :src="imagePreview" class="max-h-48 mx-auto rounded-xl mb-3">
                                                <p class="text-sm font-bold text-green-600">✓ Image selected</p>
                                            </div>
                                        </template>
                                    </div>
                                </label>

                                <button type="submit" :disabled="uploading"
                                    class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                    <span x-show="!uploading">Upload Screenshot</span>
                                    <span x-show="uploading">Uploading...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-green-50 border-2 border-green-200 rounded-[2.5rem] p-6 text-center">
                        <svg class="w-12 h-12 mx-auto mb-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="font-bold text-green-900">Payment screenshot uploaded successfully!</p>
                        <p class="text-sm text-green-700 mt-2">We'll verify your payment shortly.</p>
                    </div>
                @endif
            @endif

            <!-- Send to WhatsApp -->
            <a href="{{ $waUrl }}" target="_blank"
                class="flex items-center justify-center gap-4 w-full bg-[#25D366] text-white p-6 rounded-[2.5rem] font-black italic text-xl shadow-xl shadow-green-200 transform active:scale-95 transition">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                @if($order->payment_method === 'knet')
                    Send to WhatsApp
                @else
                    Send via WhatsApp
                @endif
            </a>

            <!-- View Summary -->
            <a href="{{ route('tenant.public', $tenant->slug) }}"
                class="flex items-center justify-center w-full bg-slate-50 text-slate-500 p-6 rounded-[2.5rem] font-bold text-lg hover:bg-slate-100 transition">
                Continue Shopping
            </a>
        </div>

        <!-- Order Tracking Card -->
        <div class="mt-12 w-full p-8 bg-white rounded-[3rem] border border-slate-100 text-left">
            <h4 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-6 border-b pb-4">Order Details</h4>
            <div class="space-y-4">
                @if($order->table_number)
                    <div class="flex justify-between font-bold">
                        <span class="text-slate-400">Table</span>
                        <span class="text-slate-900">Table {{ $order->table_number }}</span>
                    </div>
                @endif
                <div class="flex justify-between font-bold">
                    <span class="text-slate-400">Status</span>
                    <span class="text-indigo-600 uppercase">{{ $order->status }}</span>
                </div>
                <div class="flex justify-between font-bold">
                    <span class="text-slate-400">Total Amount</span>
                    <span class="text-slate-900">{{ number_format($order->total, 3) }} KWD</span>
                </div>
                <div class="flex justify-between font-bold text-sm">
                    <span class="text-slate-400">Payment Method</span>
                    <span class="text-slate-900 uppercase">{{ $order->payment_method }}</span>
                </div>
                @if($order->payment_method === 'cash')
                    <div class="flex justify-between font-bold text-sm">
                        <span class="text-slate-400">Payment Status</span>
                        <span class="text-amber-600">Pay on
                            {{ $order->delivery_type === 'delivery' ? 'Delivery' : ($order->delivery_type === 'dine_in' ? 'Table' : 'Pickup') }}</span>
                    </div>
                @elseif($order->payment_method === 'pay_later')
                    <div class="flex justify-between font-bold text-sm">
                        <span class="text-slate-400">Payment Status</span>
                        <span class="text-amber-600">Pay at checkout</span>
                    </div>
                @else
                    <div class="flex justify-between font-bold text-sm">
                        <span class="text-slate-400">Payment Status</span>
                        <span class="text-blue-600">{{ $order->payment_screenshot ? 'Submitted' : 'Awaiting Upload' }}</span>
                    </div>
                @endif
                </div>
                </div>

                <!-- Share Receipt via WhatsApp -->
                @php
                    $receiptItems = $order->items->map(fn($item) => "{$item->qty}x {$item->item_name}")->join("\n");
                    $receiptText = "🧾 *Receipt - Order #{$order->order_no}*\n\n";
                    $receiptText .= "📍 " . ($order->table_number ? "Table {$order->table_number}" : ucfirst($order->delivery_type)) . "\n\n";
                    $receiptText .= "*Items:*\n{$receiptItems}\n\n";
                    $receiptText .= "*Total:* " . number_format($order->total, 3) . " KWD\n";
                    $receiptText .= "*Status:* " . ucfirst($order->status) . "\n\n";
                    $receiptText .= "Thank you for ordering! 🙏";
                    $receiptWaUrl = "https://wa.me/?text=" . urlencode($receiptText);
                @endphp
                <div class="mt-6 w-full">
                    <a href="{{ $receiptWaUrl }}" target="_blank"
                        class="flex items-center justify-center gap-3 w-full bg-slate-100 text-slate-600 p-5 rounded-2xl font-bold text-sm hover:bg-slate-200 transition">
                        <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                        Share Receipt via WhatsApp
                    </a>
                </div>
    </div>
@endsection