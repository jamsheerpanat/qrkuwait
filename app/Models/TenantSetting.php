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
        // Handled via custom accessor/mutator for robustness
    ];

    /**
     * Get the setting value.
     */
    public function getValueAttribute($value)
    {
        if (is_null($value))
            return null;

        // Try to decode if it looks like JSON (starts with { or [ or ")
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        return $value;
    }

    /**
     * Set the setting value.
     */
    public function setValueAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['value'] = json_encode($value);
        } else {
            $this->attributes['value'] = $value;
        }
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
