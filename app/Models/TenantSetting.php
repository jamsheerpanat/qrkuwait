<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'key',
        'value',
    ];

    protected $casts = [
        'value' => 'json',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
