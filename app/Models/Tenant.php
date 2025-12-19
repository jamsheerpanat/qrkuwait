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
        // Check if relationship is loaded
        if ($this->relationLoaded('customSettings')) {
            $setting = $this->customSettings->where('key', $key)->first();
            return $setting ? $setting->value : $default;
        }

        // Otherwise query the database
        $setting = $this->customSettings()->where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public function getSettingUrl($key, $default = null)
    {
        $value = $this->getSetting($key);

        if (!$value) {
            return $default;
        }

        // If it's already a full URL, return it
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        // Otherwise, treat it as a storage path
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
