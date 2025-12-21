<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function findActiveTableOrder(int $tenantId, string $tableNumber)
    {
        return Order::where('tenant_id', $tenantId)
            ->where('table_number', $tableNumber)
            ->where('delivery_type', 'dine_in')
            ->whereIn('status', ['new', 'confirmed', 'preparing', 'ready'])
            ->orderBy('id', 'desc')
            ->first();
    }

    public function createOrder(array $data, array $items, int $tenantId)
    {
        return DB::transaction(function () use ($data, $items, $tenantId) {
            // Check for existing active order for table orders
            if ($data['delivery_type'] === 'dine_in' && !empty($data['table_number'])) {
                $activeOrder = $this->findActiveTableOrder($tenantId, $data['table_number']);

                if ($activeOrder) {
                    // Append items to existing order
                    foreach ($items as $item) {
                        $activeOrder->items()->create($item);
                    }

                    // Update totals
                    $newSubtotal = $activeOrder->subtotal + $data['subtotal'];
                    $activeOrder->update([
                        'subtotal' => $newSubtotal,
                        'total' => $newSubtotal, // Simplified
                        'notes' => $activeOrder->notes . ($data['notes'] ? "\n[Addon]: " . $data['notes'] : ''),
                    ]);

                    // Add log
                    $activeOrder->statusLogs()->create([
                        'to_status' => $activeOrder->status,
                        'notes' => 'Additional items added' . ($data['source'] === 'waiter' ? ' by waiter' : ' by customer'),
                    ]);

                    return $activeOrder;
                }
            }

            // Normal creation logic
            // Generate next order number for tenant
            $lastOrder = Order::where('tenant_id', $tenantId)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNo = $lastOrder ? (int) $lastOrder->order_no + 1 : 1001;

            $order = Order::create(array_merge($data, [
                'tenant_id' => $tenantId,
                'order_no' => (string) $nextNo,
                'status' => $data['status'] ?? 'new',
            ]));

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Initial log
            $order->statusLogs()->create([
                'to_status' => $order->status,
            ]);

            return $order;
        });
    }

    public function updateStatus(Order $order, string $newStatus, ?int $userId = null)
    {
        $oldStatus = $order->status;

        if ($oldStatus === $newStatus) {
            return $order;
        }

        $order->update(['status' => $newStatus]);

        $order->statusLogs()->create([
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'changed_by_user_id' => $userId,
        ]);

        return $order;
    }
}
