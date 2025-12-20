<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>POS - {{ $tenant->name }} | QRKuwait</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/qrkuwait-logo.png') }}">
    
    <!-- Minimal CSS - Inlined for fastest load -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0f172a;
            --card: #1e293b;
            --card-hover: #334155;
            --primary: #6366f1;
            --primary-hover: #818cf8;
            --success: #22c55e;
            --warning: #f59e0b;
            --danger: #ef4444;
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: #334155;
            --cyan: #22d3ee;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
        }
        
        /* Layout */
        .pos-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            height: 100vh;
        }
        
        /* Left Panel - Menu */
        .menu-panel {
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border);
            overflow: hidden;
        }
        
        /* Header */
        .pos-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--card);
            border-bottom: 1px solid var(--border);
        }
        .pos-logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .pos-logo img {
            height: 32px;
        }
        .pos-logo .store-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .pos-time {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 600;
        }
        .pos-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pos-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--card-hover);
            border-radius: 8px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.15s;
        }
        .pos-btn:hover {
            background: var(--primary);
            color: white;
        }
        .pos-btn.active {
            background: var(--success);
            color: white;
        }
        .pos-btn svg {
            width: 16px;
            height: 16px;
        }
        .kbd {
            display: inline-block;
            padding: 2px 6px;
            background: var(--bg);
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            margin-left: 6px;
            opacity: 0.7;
        }
        
        /* Search Bar */
        .search-bar {
            padding: 12px 16px;
            background: var(--card);
        }
        .search-input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            outline: none;
        }
        .search-input:focus {
            border-color: var(--primary);
        }
        
        /* Categories */
        .categories {
            display: flex;
            gap: 8px;
            padding: 12px 16px;
            overflow-x: auto;
            background: var(--card);
            border-bottom: 1px solid var(--border);
        }
        .categories::-webkit-scrollbar { display: none; }
        .cat-btn {
            padding: 8px 16px;
            border-radius: 20px;
            border: none;
            background: var(--bg);
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.1s;
        }
        .cat-btn:hover { background: var(--card-hover); }
        .cat-btn.active {
            background: var(--primary);
            color: white;
        }
        .cat-btn .kbd {
            background: rgba(255,255,255,0.2);
            font-size: 9px;
            margin-left: 4px;
        }
        
        /* Items Grid */
        .items-grid {
            flex: 1;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            padding: 16px;
            overflow-y: auto;
            align-content: start;
        }
        .item-card {
            background: var(--card);
            border-radius: 16px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.15s;
            border: 2px solid transparent;
        }
        .item-card:hover {
            background: var(--card-hover);
            transform: scale(1.02);
        }
        .item-card:active {
            transform: scale(0.98);
            border-color: var(--primary);
        }
        .item-name {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .item-price {
            font-size: 16px;
            font-weight: 800;
            color: var(--cyan);
        }
        .item-sku {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 4px;
        }
        
        /* Right Panel - Cart */
        .cart-panel {
            display: flex;
            flex-direction: column;
            background: var(--card);
        }
        
        .cart-header {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cart-title {
            font-size: 18px;
            font-weight: 800;
        }
        .cart-clear {
            padding: 6px 12px;
            border-radius: 8px;
            border: none;
            background: var(--danger);
            color: white;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }
        
        /* Tab Navigation */
        .cart-tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
        }
        .cart-tab {
            flex: 1;
            padding: 12px;
            border: none;
            background: transparent;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }
        .cart-tab.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }
        .cart-tab .badge {
            position: absolute;
            top: 6px;
            right: 10px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: 700;
        }
        
        /* Cart Items */
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
        }
        .cart-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: var(--bg);
            border-radius: 12px;
            margin-bottom: 8px;
        }
        .cart-item-info {
            flex: 1;
        }
        .cart-item-name {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .cart-item-price {
            font-size: 12px;
            color: var(--cyan);
            font-weight: 700;
        }
        .qty-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .qty-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: none;
            background: var(--card-hover);
            color: var(--text);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }
        .qty-btn:hover { background: var(--primary); }
        .qty-value {
            font-size: 14px;
            font-weight: 700;
            min-width: 24px;
            text-align: center;
        }
        
        /* Order Card (QR Orders) */
        .order-card {
            background: var(--bg);
            border-radius: 12px;
            padding: 12px;
            margin-bottom: 8px;
            border-left: 4px solid var(--warning);
        }
        .order-card.confirmed { border-left-color: var(--success); }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .order-no {
            font-weight: 800;
            font-size: 14px;
        }
        .order-type {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .order-type.delivery { background: #3b82f6; color: white; }
        .order-type.pickup { background: #22c55e; color: white; }
        .order-customer {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }
        .order-total {
            font-size: 16px;
            font-weight: 800;
            color: var(--cyan);
        }
        .order-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
        }
        .order-btn {
            flex: 1;
            padding: 10px;
            border-radius: 8px;
            border: none;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.15s;
        }
        .order-btn.accept { background: var(--success); color: white; }
        .order-btn.print { background: var(--border); color: var(--text); }
        .order-btn.cancel { background: var(--danger); color: white; }
        
        /* Cart Summary */
        .cart-summary {
            padding: 16px;
            border-top: 1px solid var(--border);
            background: var(--bg);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .summary-row.total {
            font-size: 20px;
            font-weight: 800;
            color: var(--cyan);
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }
        
        /* Payment Buttons */
        .payment-btns {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            margin-top: 12px;
        }
        .pay-btn {
            padding: 16px;
            border-radius: 12px;
            border: none;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.15s;
        }
        .pay-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .pay-btn.cash { background: var(--success); color: white; }
        .pay-btn.card { background: var(--primary); color: white; }
        .pay-btn:hover:not(:disabled) { transform: scale(1.02); }
        
        /* Footer */
        .pos-footer {
            padding: 8px 16px;
            background: var(--bg);
            border-top: 1px solid var(--border);
            text-align: center;
            font-size: 10px;
            color: var(--text-muted);
        }
        .pos-footer a {
            color: var(--cyan);
            text-decoration: none;
        }
        
        /* Empty State */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: var(--text-muted);
            text-align: center;
            padding: 24px;
        }
        .empty-state svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: 0.5;
        }
        
        /* Modal */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal {
            background: var(--card);
            border-radius: 24px;
            padding: 24px;
            width: 90%;
            max-width: 400px;
        }
        .modal-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 16px;
        }
        .modal input {
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            margin-bottom: 12px;
        }
        .modal-btns {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }
        .modal-btns button {
            flex: 1;
            padding: 14px;
            border-radius: 12px;
            border: none;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
        .modal-btns .cancel { background: var(--border); color: var(--text); }
        .modal-btns .confirm { background: var(--success); color: white; }
        
        /* Toast */
        .toast {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--success);
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            z-index: 1001;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translate(-50%, 20px); }
            to { opacity: 1; transform: translate(-50%, 0); }
        }
        
        /* Loading */
        .loading {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid var(--border);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        
        /* Responsive */
        @media (max-width: 768px) {
            .pos-container {
                grid-template-columns: 1fr;
            }
            .cart-panel {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                height: 60%;
                transform: translateY(calc(100% - 70px));
                transition: transform 0.3s;
                border-radius: 24px 24px 0 0;
                box-shadow: 0 -10px 40px rgba(0,0,0,0.5);
            }
            .cart-panel.open {
                transform: translateY(0);
            }
            .items-grid {
                padding-bottom: 100px;
            }
        }
    </style>
</head>
<body>
    <div id="app" class="pos-container">
        <!-- Left Panel - Menu -->
        <div class="menu-panel">
            <div class="pos-header">
                <div class="pos-logo">
                    <img src="{{ asset('images/qrkuwait-logo.png') }}" alt="QRKuwait">
                    <span class="store-name">{{ $tenant->name }}</span>
                </div>
                <div class="pos-actions">
                    <button class="pos-btn" onclick="toggleFullscreen()" title="Toggle Fullscreen (F11)">
                        <svg id="fullscreenIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                        <span class="kbd">F11</span>
                    </button>
                    <button class="pos-btn" onclick="showKeyboardShortcuts()" title="Keyboard Shortcuts">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <span class="kbd">?</span>
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="pos-btn">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        Dashboard
                    </a>
                    <div class="pos-time" id="clock">--:--</div>
                </div>
            </div>
            
            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search items by name or SKU..." 
                       id="searchInput" oninput="filterItems()">
            </div>
            
            <div class="categories">
                <button class="cat-btn active" data-cat="all" onclick="filterCategory('all', this)">All Items</button>
                @foreach($categories as $cat)
                    <button class="cat-btn" data-cat="{{ $cat->id }}" onclick="filterCategory({{ $cat->id }}, this)">
                        {{ $cat->name['en'] ?? $cat->name['ar'] ?? 'Category' }}
                    </button>
                @endforeach
            </div>
            
            <div class="items-grid" id="itemsGrid">
                @foreach($items as $item)
                    <div class="item-card" data-id="{{ $item->id }}" data-cat="{{ $item->category_id }}" 
                         data-name="{{ ($item->name['en'] ?? '') . ' ' . ($item->name['ar'] ?? '') }}"
                         data-sku="{{ $item->sku }}"
                         data-price="{{ $item->price }}"
                         onclick="addToCart({{ $item->id }}, '{{ addslashes($item->name['en'] ?? $item->name['ar'] ?? 'Item') }}', {{ $item->price }})">
                        <div class="item-name">{{ $item->name['en'] ?? $item->name['ar'] ?? 'Item' }}</div>
                        <div class="item-price">{{ number_format($item->price, 3) }} KWD</div>
                        @if($item->sku)
                            <div class="item-sku">{{ $item->sku }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Right Panel - Cart -->
        <div class="cart-panel" id="cartPanel">
            <div class="cart-header">
                <span class="cart-title">Current Sale</span>
                <button class="cart-clear" onclick="clearCart()">Clear</button>
            </div>
            
            <div class="cart-tabs">
                <button class="cart-tab active" data-tab="cart" onclick="switchTab('cart', this)">
                    Cart
                </button>
                <button class="cart-tab" data-tab="orders" onclick="switchTab('orders', this)">
                    QR Orders
                    <span class="badge" id="orderBadge" style="display: none;">0</span>
                </button>
            </div>
            
            <!-- Cart Tab -->
            <div class="cart-items" id="cartTab">
                <div class="empty-state" id="emptyCart">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <p>Cart is empty</p>
                    <p style="font-size: 12px; margin-top: 4px;">Tap items to add</p>
                </div>
                <div id="cartItemsList"></div>
            </div>
            
            <!-- Orders Tab -->
            <div class="cart-items" id="ordersTab" style="display: none;">
                <div class="loading" id="ordersLoading">
                    <div class="spinner"></div>
                </div>
                <div id="ordersList"></div>
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary" id="cartSummary">
                <div class="summary-row">
                    <span>Items</span>
                    <span id="itemCount">0</span>
                </div>
                <div class="summary-row total">
                    <span>TOTAL</span>
                    <span id="cartTotal">0.000 KWD</span>
                </div>
                <div class="payment-btns">
                    <button class="pay-btn cash" id="payCash" onclick="checkout('cash')" disabled>
                        💵 CASH
                    </button>
                    <button class="pay-btn card" id="payCard" onclick="checkout('knet')" disabled>
                        💳 KNET
                    </button>
                </div>
            </div>
            <!-- Footer Credit -->
            <div class="pos-footer">
                Powered by <a href="https://octonics.io" target="_blank">Octonics Innovations</a>
            </div>
        </div>
    </div>
    
    <!-- Toast -->
    <div class="toast" id="toast" style="display: none;"></div>
    
    <!-- Customer Modal -->
    <div class="modal-overlay" id="customerModal" style="display: none;" onclick="event.target === this && closeModal()">
        <div class="modal">
            <div class="modal-title">Customer Details</div>
            <input type="text" id="customerName" placeholder="Customer Name (optional)">
            <input type="tel" id="customerMobile" placeholder="Mobile Number (optional)">
            <textarea id="orderNotes" placeholder="Order Notes" style="width:100%;padding:12px;border-radius:12px;border:1px solid var(--border);background:var(--bg);color:var(--text);font-size:14px;resize:none;height:80px;"></textarea>
            <div class="modal-btns">
                <button class="cancel" onclick="closeModal()">Cancel</button>
                <button class="confirm" id="confirmPayBtn">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // State
        let cart = [];
        let pendingOrders = [];
        let selectedPayment = 'cash';
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        
        // Clock
        function updateClock() {
            const now = new Date();
            document.getElementById('clock').textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', minute: '2-digit', hour12: true 
            });
        }
        updateClock();
        setInterval(updateClock, 1000);
        
        // Add to Cart
        function addToCart(id, name, price) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.qty += 1;
            } else {
                cart.push({ id, name, price, qty: 1 });
            }
            renderCart();
            showToast(`Added: ${name}`);
        }
        
        // Render Cart
        function renderCart() {
            const list = document.getElementById('cartItemsList');
            const empty = document.getElementById('emptyCart');
            
            if (cart.length === 0) {
                list.innerHTML = '';
                empty.style.display = 'flex';
                document.getElementById('payCash').disabled = true;
                document.getElementById('payCard').disabled = true;
                document.getElementById('itemCount').textContent = '0';
                document.getElementById('cartTotal').textContent = '0.000 KWD';
                return;
            }
            
            empty.style.display = 'none';
            document.getElementById('payCash').disabled = false;
            document.getElementById('payCard').disabled = false;
            
            let html = '';
            let total = 0;
            let itemCount = 0;
            
            cart.forEach((item, index) => {
                const lineTotal = item.price * item.qty;
                total += lineTotal;
                itemCount += item.qty;
                
                html += `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">${lineTotal.toFixed(3)} KWD</div>
                        </div>
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty(${index}, -1)">−</button>
                            <span class="qty-value">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                    </div>
                `;
            });
            
            list.innerHTML = html;
            document.getElementById('itemCount').textContent = itemCount;
            document.getElementById('cartTotal').textContent = total.toFixed(3) + ' KWD';
        }
        
        // Update Quantity
        function updateQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }
            renderCart();
        }
        
        // Clear Cart
        function clearCart() {
            cart = [];
            renderCart();
        }
        
        // Checkout
        function checkout(method) {
            selectedPayment = method;
            document.getElementById('customerModal').style.display = 'flex';
            document.getElementById('confirmPayBtn').onclick = () => processPayment();
        }
        
        // Process Payment
        async function processPayment() {
            const name = document.getElementById('customerName').value;
            const mobile = document.getElementById('customerMobile').value;
            const notes = document.getElementById('orderNotes').value;
            
            closeModal();
            
            try {
                const response = await fetch('{{ route("admin.pos.create") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({
                        items: cart.map(item => ({
                            id: item.id,
                            qty: item.qty,
                            price: item.price
                        })),
                        customer_name: name,
                        customer_mobile: mobile,
                        payment_method: selectedPayment,
                        notes: notes
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast(`✓ Order #${data.order.order_no} Created!`);
                    clearCart();
                    // Open print receipt in new tab
                    window.open(`/admin/pos/receipt/${data.order.id}`, '_blank');
                } else {
                    showToast('❌ Failed to create order');
                }
            } catch (error) {
                console.error(error);
                showToast('❌ Error processing order');
            }
        }
        
        // Close Modal
        function closeModal() {
            document.getElementById('customerModal').style.display = 'none';
            document.getElementById('customerName').value = '';
            document.getElementById('customerMobile').value = '';
            document.getElementById('orderNotes').value = '';
        }
        
        // Switch Tab
        function switchTab(tab, btn) {
            document.querySelectorAll('.cart-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            
            document.getElementById('cartTab').style.display = tab === 'cart' ? 'block' : 'none';
            document.getElementById('ordersTab').style.display = tab === 'orders' ? 'block' : 'none';
            document.getElementById('cartSummary').style.display = tab === 'cart' ? 'block' : 'none';
            
            if (tab === 'orders') {
                loadOrders();
            }
        }
        
        // Load QR Orders
        async function loadOrders() {
            document.getElementById('ordersLoading').style.display = 'flex';
            document.getElementById('ordersList').innerHTML = '';
            
            try {
                const response = await fetch('{{ route("admin.pos.pending") }}');
                pendingOrders = await response.json();
                renderOrders();
            } catch (error) {
                console.error(error);
                document.getElementById('ordersList').innerHTML = '<div class="empty-state"><p>Failed to load orders</p></div>';
            }
            
            document.getElementById('ordersLoading').style.display = 'none';
        }
        
        // Render Orders
        function renderOrders() {
            const list = document.getElementById('ordersList');
            
            if (pendingOrders.length === 0) {
                list.innerHTML = '<div class="empty-state"><p>No pending orders</p></div>';
                return;
            }
            
            let html = '';
            pendingOrders.forEach(order => {
                const statusClass = order.status === 'confirmed' ? 'confirmed' : '';
                html += `
                    <div class="order-card ${statusClass}">
                        <div class="order-header">
                            <span class="order-no">#${order.order_no}</span>
                            <span class="order-type ${order.delivery_type}">${order.delivery_type}</span>
                        </div>
                        <div class="order-customer">
                            👤 ${order.customer_name} • 📞 ${order.customer_mobile}
                        </div>
                        <div style="font-size: 11px; color: var(--text-muted); margin-bottom: 8px;">
                            ${order.items.map(i => `${i.qty}x ${i.item_name}`).join(', ')}
                        </div>
                        <div class="order-total">${parseFloat(order.total).toFixed(3)} KWD</div>
                        <div class="order-actions">
                            ${order.status === 'new' ? `
                                <button class="order-btn accept" onclick="acceptOrder(${order.id})">✓ Accept</button>
                            ` : `
                                <button class="order-btn print" onclick="printOrder(${order.id})">🖨 Print</button>
                            `}
                            <button class="order-btn print" onclick="updateOrderStatus(${order.id}, 'preparing')">Kitchen</button>
                        </div>
                    </div>
                `;
            });
            
            list.innerHTML = html;
        }
        
        // Accept Order
        async function acceptOrder(id) {
            try {
                const response = await fetch(`/admin/pos/accept/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    }
                });
                const data = await response.json();
                if (data.success) {
                    showToast(data.message);
                    loadOrders();
                }
            } catch (error) {
                showToast('❌ Error accepting order');
            }
        }
        
        // Update Order Status
        async function updateOrderStatus(id, status) {
            try {
                const response = await fetch(`/admin/pos/status/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: JSON.stringify({ status })
                });
                const data = await response.json();
                if (data.success) {
                    showToast(data.message);
                    loadOrders();
                }
            } catch (error) {
                showToast('❌ Error updating order');
            }
        }
        
        // Print Order
        function printOrder(id) {
            window.open(`/admin/pos/receipt/${id}`, '_blank');
        }
        
        // Filter Category
        function filterCategory(catId, btn) {
            document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const cards = document.querySelectorAll('.item-card');
            cards.forEach(card => {
                if (catId === 'all' || card.dataset.cat == catId) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Filter Items (Search)
        function filterItems() {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.item-card');
            
            cards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                const sku = (card.dataset.sku || '').toLowerCase();
                
                if (name.includes(search) || sku.includes(search)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        // Show Toast
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 2000);
        }
        
        // Poll for new orders
        async function pollOrderCount() {
            try {
                const response = await fetch('{{ route("admin.pos.count") }}');
                const counts = await response.json();
                const badge = document.getElementById('orderBadge');
                const total = counts.new + counts.confirmed;
                
                if (total > 0) {
                    badge.textContent = total;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }
            } catch (error) {}
        }
        
        // Initial load
        pollOrderCount();
        setInterval(pollOrderCount, 10000); // Poll every 10 seconds
        
        // Mobile cart toggle
        document.getElementById('cartPanel').addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && e.target.closest('.cart-header')) {
                this.classList.toggle('open');
            }
        });

            // ========== ADVANCED POS FEATURES ==========

            // Sound Effects
            const sounds = {
                add: new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleHlvfZy9x6dxJAo1kr/fvIFJGSh+1PbRjkEaKZnP8c+PN0o='),
                checkout: new Audio('data:audio/wav;base64,UklGRjIBAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ4BAAB+fn5+fn5+fn5/gH9/f4CAgICBgYGCgoKDg4OEhISFhYWGhoaHh4eIiIiJiYmKioqLi4uMjIyNjY2Ojo6Pj4+QkJCRkZGSkpKTk5OUlJSVlZWWlpaXl5eYmJiZmZmampqbm5ucnJydnZ2enp6fn5+goKChoaGioqKjo6OkpKSlpaWmpqanp6eoqKipqamqqqqrq6usrKytra2urq6vr6+wsLCxsbGysrKzs7O0tLS1tbW2tra3t7e4uLi5ubm6urq7u7u8vLy9vb2+vr6/v7/AwMDBwcHCwsLDw8PExMTFxcXGxsbHx8fIyMjJycnKysrLy8vMzMzNzc3Ozs7Pz8/Q0NDR0dHS0tLT09PU1NTV1dXW1tbX19fY2NjZ2dna2trb29vc3Nzd3d3e3t7f39/g4ODh4eHi4uLj4+Pk5OTl5eXm5ubn5+fo6Ojp6enq6urr6+vs7Ozt7e3u7u7v7+/w8PDx8fHy8vLz8/P09PT19fX29vb39/f4+Pj5+fn6+vr7+/v8/Pz9/f3+/v7///8='),
                error: new Audio('data:audio/wav;base64,UklGRjIBAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQ4BAAB/f39/f398fHt6eXl4eHd3dnZ1dXR0c3NycnFxcHBvb25ubW1sbGtramppamppaWhoZ2dmZmVlZGRjY2JiYWFgYF9fXl5dXVxcW1taWllZWFhXV1ZWVVVUVFNTUlJRUVBQT09OTk1NTExLS0pKSUlISEdHRkZFRURUQ0NCQkFBQEA/Pz4+PT08PDt7Ojo5OTg4Nzc2NjU1NTQ0MzMyMjExMDAvLy4uLS0sLCsrKiopKSgoJycmJiUlJCQjIyIiISEgIB8fHh4dHRwcGxsaGhkZGBgXFxYWFRUUFBMTEhIRERAQDw8ODg0NDAwLCwoKCQkICAgHBwYGBQUEBAMDAgIBAQAA'),
            };

            function playSound(type) {
                try {
                    sounds[type]?.play().catch(() => { });
                } catch (e) { }
            }

            // Enhanced addToCart with sound
            const originalAddToCart = addToCart;
            addToCart = function (id, name, price) {
                originalAddToCart(id, name, price);
                playSound('add');
            };

            // Fullscreen Toggle
            let isFullscreen = false;
            function toggleFullscreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen().then(() => {
                        isFullscreen = true;
                        document.getElementById('fullscreenIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        isFullscreen = false;
                        document.getElementById('fullscreenIcon').innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>';
                    });
                }
            }

            // Keyboard Shortcuts
            document.addEventListener('keydown', function (e) {
                // Skip if typing in input
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                    if (e.key === 'Escape') e.target.blur();
                    return;
                }

                switch (e.key.toLowerCase()) {
                    case 'f11':
                        e.preventDefault();
                        toggleFullscreen();
                        break;
                    case '/':
                    case 's':
                        e.preventDefault();
                        document.getElementById('searchInput').focus();
                        break;
                    case 'escape':
                        e.preventDefault();
                        closeModal();
                        document.getElementById('searchInput').value = '';
                        filterItems();
                        break;
                    case 'c':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        if (cart.length > 0) checkout('cash');
                        break;
                    case 'k':
                        if (e.ctrlKey || e.metaKey) return;
                        e.preventDefault();
                        if (cart.length > 0) checkout('knet');
                        break;
                    case 'x':
                        e.preventDefault();
                        clearCart();
                        break;
                    case 'o':
                        e.preventDefault();
                        document.querySelector('.tabs button:last-child').click();
                        break;
                    case '?':
                        e.preventDefault();
                        showKeyboardShortcuts();
                        break;
                    case '1': case '2': case '3': case '4': case '5':
                    case '6': case '7': case '8': case '9':
                        // Quick category switch
                        const catBtns = document.querySelectorAll('.cat-btn');
                        const idx = parseInt(e.key) - 1;
                        if (catBtns[idx]) {
                            catBtns[idx].click();
                        }
                        break;
                }
            });

            // Keyboard Shortcuts Modal
            function showKeyboardShortcuts() {
                const modal = document.createElement('div');
                modal.id = 'shortcutsModal';
                modal.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:9999;';
                modal.innerHTML = `
                <div style="background:var(--card);border-radius:16px;padding:24px;max-width:400px;width:90%;">
                    <h3 style="font-size:18px;font-weight:700;margin-bottom:16px;display:flex;align-items:center;gap:8px;">
                        <svg style="width:20px;height:20px" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        Keyboard Shortcuts
                    </h3>
                    <div style="display:grid;gap:8px;font-size:13px;">
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Search Items</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">S or /</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Fullscreen Toggle</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">F11</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Cash Checkout</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">C</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>KNET Checkout</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">K</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Clear Cart</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">X</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>View Orders</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">O</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Quick Category</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">1-9</kbd></div>
                        <div style="display:flex;justify-content:space-between;padding:8px;background:var(--bg);border-radius:8px;"><span>Close/Cancel</span><kbd style="background:var(--card-hover);padding:2px 8px;border-radius:4px;font-weight:600;">ESC</kbd></div>
                    </div>
                    <button onclick="this.closest('#shortcutsModal').remove()" style="width:100%;margin-top:16px;padding:12px;background:var(--primary);color:white;border:none;border-radius:8px;font-weight:600;cursor:pointer;">Got it!</button>
                </div>
            `;
                modal.onclick = (e) => { if (e.target === modal) modal.remove(); };
                document.body.appendChild(modal);
            }

            // Focus search on load (productivity)
            setTimeout(() => document.getElementById('searchInput').focus(), 500);

            // Enhanced checkout with sound
            const originalCheckout = checkout;
            checkout = function (method) {
                playSound('checkout');
                originalCheckout(method);
            };
    </script>
</body>
</html>
