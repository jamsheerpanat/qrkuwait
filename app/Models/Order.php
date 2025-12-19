<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'branch_id',
        'order_no',
        'customer_name',
        'customer_mobile',
        'delivery_type',
        'address',
        'subtotal',
        'delivery_fee',
        'tax',
        'total',
        'payment_method',
        'payment_status',
        'payment_screenshot',
        'status',
        'source',
        'notes',
    ];

    protected $casts = [
        'address' => 'array',
        'subtotal' => 'decimal:3',
        'delivery_fee' => 'decimal:3',
        'tax' => 'decimal:3',
        'total' => 'decimal:3',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
    }
}
