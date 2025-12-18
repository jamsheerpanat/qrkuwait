<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'item_name',
        'qty',
        'unit_label',
        'price',
        'line_total',
        'notes',
        'selected_variants',
        'selected_modifiers',
    ];

    protected $casts = [
        'selected_variants' => 'array',
        'selected_modifiers' => 'array',
        'qty' => 'decimal:3',
        'price' => 'decimal:3',
        'line_total' => 'decimal:3',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
