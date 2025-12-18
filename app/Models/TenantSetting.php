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
        // No global cast for value as it can be mixed (string, boolean, array)
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
