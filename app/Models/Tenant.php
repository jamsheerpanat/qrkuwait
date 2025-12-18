<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'status',
        'default_language',
        'timezone',
        'api_key',
        'settings',
        'currency',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    protected $appends = ['logo_url', 'cover_url'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function customSettings()
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getSetting($key, $default = null)
    {
        // Use loaded relationship to avoid N+1 and naming conflicts
        $settings = $this->relationLoaded('customSettings')
            ? $this->customSettings
            : $this->customSettings();

        $setting = $settings->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function getSettingUrl($key, $default = null)
    {
        $value = $this->getSetting($key);
        if (!$value)
            return $default;
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        return asset('storage/' . $value);
    }

    public function getLogoUrlAttribute()
    {
        return $this->getSettingUrl('logo');
    }

    public function getCoverUrlAttribute()
    {
        return $this->getSettingUrl('cover');
    }
}
