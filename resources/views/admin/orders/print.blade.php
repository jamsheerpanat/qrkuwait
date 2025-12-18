<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->order_no }} - Print</title>
    <style>
        @page {
            size: 80mm 200mm;
            margin: 0;
        }

        body {
            font-family: 'Courier', monospace;
            font-size: 14px;
            margin: 0;
            padding: 10mm;
            width: 60mm;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .divider {
            border-bottom: 2px dashed #000;
            margin: 10px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .item-list {
            margin-top: 10px;
        }

        .qr {
            margin-top: 15px;
        }

        table {
            width: 100%;
        }

        th {
            text-align: left;
        }

        td {
            padding: 5px 0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="center">
        <h2 style="margin-bottom: 5px;">{{ $tenant->name }}</h2>
        <div class="bold">Order #{{ $order->order_no }}</div>
        <div>{{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div class="divider"></div>
    </div>

    <div class="bold">Customer:</div>
    <div>{{ $order->customer_name }}</div>
    <div>{{ $order->customer_mobile }}</div>
    <div>Method: {{ strtoupper($order->delivery_type) }}</div>
    <div class="divider"></div>

    <table class="item-list">
        <thead>
            <tr>
                <th>Item</th>
                <th class="center">Qty</th>
                <th style="text-align: right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td class="center">{{ (float) $item->qty }}</td>
                    <td style="text-align: right">{{ number_format($item->line_total, 3) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="bold flex">
        <span>Subtotal:</span>
        <span>{{ number_format($order->subtotal, 3) }}</span>
    </div>
    @if($order->delivery_fee > 0)
        <div class="bold flex">
            <span>Delivery:</span>
            <span>{{ number_format($order->delivery_fee, 3) }}</span>
        </div>
    @endif
    <div class="bold flex" style="font-size: 18px; margin-top: 10px;">
        <span>TOTAL:</span>
        <span>{{ number_format($order->total, 3) }} KD</span>
    </div>

    <div class="divider"></div>
    <div class="center qr">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate($order->order_no) !!}
        <div style="margin-top: 5px; font-size: 10px;">Thank you for your order!</div>
    </div>

    <div class="no-print center" style="margin-top: 20px;">
        <button onclick="window.close()" style="padding: 10px 20px;">Close Window</button>
    </div>
</body>

</html>