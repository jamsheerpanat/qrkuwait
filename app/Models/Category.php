<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'name',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'name' => 'array',
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class)->orderBy('sort_order');
    }

    // Helper for bilingual name
    public function getNameAttribute($value)
    {
        $name = json_decode($value, true);
        $locale = app()->getLocale();
        return $name[$locale] ?? ($name['en'] ?? '');
    }
}
