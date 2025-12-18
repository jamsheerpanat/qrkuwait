<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderStatusLog;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data, array $items, int $tenantId)
    {
        return DB::transaction(function () use ($data, $items, $tenantId) {
            // Generate next order number for tenant
            $lastOrder = Order::where('tenant_id', $tenantId)
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $nextNo = $lastOrder ? (int) $lastOrder->order_no + 1 : 1001;

            $order = Order::create(array_merge($data, [
                'tenant_id' => $tenantId,
                'order_no' => (string) $nextNo,
                'status' => 'new',
            ]));

            foreach ($items as $item) {
                $order->items()->create($item);
            }

            // Initial log
            $order->statusLogs()->create([
                'to_status' => 'new',
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
