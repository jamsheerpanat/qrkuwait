<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppFormatter
{
    public function format(Order $order, string $locale = 'en'): string
    {
        $tenantName = $order->tenant->name;
        $orderNo = $order->order_no;
        $items = $order->items;

        if ($locale === 'ar') {
            $msg = "⚡️ *طلب جديد من {$tenantName}* ⚡️\n\n";
            $msg .= "🔢 رقم الطلب: #{$orderNo}\n";
            $msg .= "👤 العميل: {$order->customer_name}\n";
            $msg .= "📞 الهاتف: {$order->customer_mobile}\n";
            $msg .= "🚚 النوع: " . ($order->delivery_type === 'delivery' ? 'توصيل' : 'استلام') . "\n";
            $msg .= "💳 الدفع: " . ($order->payment_method === 'knet' ? 'كي-نت' : 'كاش') . "\n";

            if ($order->delivery_type === 'delivery' && $order->address) {
                $addr = $order->address;
                $msg .= "📍 العنوان: " . ($addr['area'] ?? '') . ", " . ($addr['block'] ?? '') . ", " . ($addr['house'] ?? '') . "\n";
                if (!empty($addr['location_url'])) {
                    $msg .= "🗺️ الموقع: " . $addr['location_url'] . "\n";
                }
            }

            $msg .= "\n📝 *الأصناف:* \n";
            foreach ($items as $item) {
                $msg .= "▫️ {$item->qty}x {$item->item_name} = " . number_format((float) $item->line_total, 3) . " د.ك\n";
            }

            $msg .= "\n💰 *المجموع:* \n";
            $msg .= "الفرعي: " . number_format((float) $order->subtotal, 3) . " د.ك\n";
            if ($order->delivery_fee > 0)
                $msg .= "التوصيل: " . number_format((float) $order->delivery_fee, 3) . " د.ك\n";
            $msg .= "📦 *الإجمالي: " . number_format((float) $order->total, 3) . " د.ك*\n\n";
            $msg .= "⏰ " . $order->created_at->format('Y-m-d H:i');
        } else {
            $msg = "⚡️ *New Order: {$tenantName}* ⚡️\n\n";
            $msg .= "🔢 Order No: #{$orderNo}\n";
            $msg .= "👤 Customer: {$order->customer_name}\n";
            $msg .= "📞 Mobile: {$order->customer_mobile}\n";
            $msg .= "🚚 Type: " . ucfirst($order->delivery_type) . "\n";
            $msg .= "💳 Payment: " . ($order->payment_method === 'knet' ? 'KNET' : 'Cash') . "\n";

            if ($order->delivery_type === 'delivery' && $order->address) {
                $addr = $order->address;
                $msg .= "📍 Address: " . ($addr['area'] ?? '') . ", Block " . ($addr['block'] ?? '') . ", House " . ($addr['house'] ?? '') . "\n";
                if (!empty($addr['location_url'])) {
                    $msg .= "🗺️ Location: " . $addr['location_url'] . "\n";
                }
            }

            $msg .= "\n📝 *Items:* \n";
            foreach ($items as $item) {
                $msg .= "▫️ {$item->qty}x {$item->item_name} = " . number_format((float) $item->line_total, 3) . " KWD\n";
            }

            $msg .= "\n💰 *Summary:* \n";
            $msg .= "Subtotal: " . number_format((float) $order->subtotal, 3) . " KWD\n";
            if ($order->delivery_fee > 0)
                $msg .= "Delivery: " . number_format((float) $order->delivery_fee, 3) . " KWD\n";
            $msg .= "📦 *Total: " . number_format((float) $order->total, 3) . " KWD*\n\n";
            $msg .= "⏰ " . $order->created_at->format('Y-m-d H:i');
        }

        return urlencode($msg);
    }

    public function getWhatsAppUrl(Order $order, string $locale = 'en'): string
    {
        $branch = $order->branch ?: $order->tenant->branches()->where('is_default', true)->first();
        $phone = $branch ? preg_replace('/[^0-9]/', '', $branch->whatsapp_number) : '';

        // Ensure phone starts with 965 if not present
        if (strlen($phone) === 8)
            $phone = '965' . $phone;

        return "https://wa.me/{$phone}?text=" . $this->format($order, $locale);
    }
}
