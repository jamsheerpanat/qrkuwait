<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'from_status',
        'to_status',
        'changed_by_user_id',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
