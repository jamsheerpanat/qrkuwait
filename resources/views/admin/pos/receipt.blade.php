<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=80mm">
    <title>Receipt #{{ $order->order_no }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        @page {
            size: 80mm auto;
            margin: 0;
        }
        
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            width: 80mm;
            padding: 8mm;
            background: white;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2px dashed #000;
        }
        
        .store-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        
        .order-info {
            font-size: 14px;
            font-weight: 700;
            margin: 8px 0;
        }
        
        .source-badge {
            display: inline-block;
            padding: 2px 8px;
            background: #000;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            border-radius: 4px;
            text-transform: uppercase;
        }
        
        .datetime {
            font-size: 11px;
            color: #555;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 12px 0;
        }
        
        .customer-info {
            margin-bottom: 12px;
        }
        
        .customer-info p {
            font-size: 11px;
            margin-bottom: 2px;
        }
        
        .items-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .items-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            padding: 4px 0;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }
        
        .items-table th:last-child {
            text-align: right;
        }
        
        .items-table td {
            padding: 6px 0;
            vertical-align: top;
            font-size: 11px;
        }
        
        .items-table td:last-child {
            text-align: right;
            font-weight: 700;
        }
        
        .item-name {
            font-weight: 600;
        }
        
        .item-qty {
            color: #555;
        }
        
        .totals {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 2px solid #000;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 12px;
        }
        
        .total-row.grand {
            font-size: 16px;
            font-weight: 700;
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px dashed #000;
        }
        
        .payment-info {
            margin-top: 12px;
            padding: 8px;
            background: #f0f0f0;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
        }
        
        .footer {
            margin-top: 16px;
            text-align: center;
            padding-top: 12px;
            border-top: 2px dashed #000;
        }
        
        .footer p {
            font-size: 11px;
            margin-bottom: 4px;
        }
        
        .thank-you {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        
        .notes {
            margin-top: 12px;
            padding: 8px;
            background: #fffbeb;
            border: 1px solid #fbbf24;
            font-size: 11px;
        }
        
        .notes strong {
            display: block;
            margin-bottom: 4px;
        }
        
        @media print {
            body { 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print { display: none !important; }
        }
        
        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #000;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <button class="print-btn no-print" onclick="window.print()">🖨 Print Receipt</button>
    
    <div class="header">
        <div class="store-name">{{ $tenant->name }}</div>
        <div class="order-info">ORDER #{{ $order->order_no }}</div>
        <span class="source-badge">{{ $order->source ?? 'POS' }}</span>
        <div class="datetime">{{ $order->created_at->format('M d, Y • h:i A') }}</div>
    </div>
    
    @if($order->customer_name && $order->customer_name !== 'Walk-in Customer')
        <div class="customer-info">
            <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
            @if($order->customer_mobile)
                <p><strong>Mobile:</strong> {{ $order->customer_mobile }}</p>
            @endif
            @if($order->delivery_type === 'delivery' && $order->address)
                <p><strong>Delivery:</strong> 
                    {{ $order->address['area'] ?? '' }}{{ !empty($order->address['block']) ? ', Block ' . $order->address['block'] : '' }}{{ !empty($order->address['house']) ? ', House ' . $order->address['house'] : '' }}
                </p>
            @endif
        </div>
    @endif
    
    <div class="divider"></div>
    
    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->item_name }}</div>
                        <div class="item-qty">{{ (float) $item->qty }} × {{ number_format($item->price, 3) }}</div>
                    </td>
                    <td>{{ number_format($item->line_total, 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="totals">
        <div class="total-row">
            <span>Subtotal</span>
            <span>{{ number_format($order->subtotal, 3) }} KWD</span>
        </div>
        @if($order->delivery_fee > 0)
            <div class="total-row">
                <span>Delivery</span>
                <span>{{ number_format($order->delivery_fee, 3) }} KWD</span>
            </div>
        @endif
        @if($order->tax > 0)
            <div class="total-row">
                <span>Tax</span>
                <span>{{ number_format($order->tax, 3) }} KWD</span>
            </div>
        @endif
        <div class="total-row grand">
            <span>TOTAL</span>
            <span>{{ number_format($order->total, 3) }} KWD</span>
        </div>
    </div>
    
    <div class="payment-info">
        💳 {{ strtoupper($order->payment_method ?? 'CASH') }}
    </div>
    
    @if($order->notes)
        <div class="notes">
            <strong>📝 Notes:</strong>
            {{ $order->notes }}
        </div>
    @endif
    
    <div class="footer">
        <p class="thank-you">Thank you!</p>
        <p>{{ $tenant->name }}</p>
        <p style="font-size: 9px; color: #888; margin-top: 8px;">Powered by QRKuwait</p>
        <p style="font-size: 8px; color: #aaa;">octonics.io</p>
    </div>
    
    <script>
        // Auto-print on load (optional)
        // window.onload = () => window.print();
    </script>
</body>
</html>
