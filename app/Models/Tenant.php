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

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function settings()
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
