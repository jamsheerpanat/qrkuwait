<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'whatsapp_number',
        'address',
        'is_default',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
