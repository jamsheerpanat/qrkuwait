<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'category_id',
        'name',
        'description',
        'price',
        'sku',
        'image',
        'is_active',
        'is_weighted',
        'unit_label',
        'sort_order',
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'price' => 'decimal:3',
        'is_active' => 'boolean',
        'is_weighted' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function modifiers()
    {
        return $this->hasMany(ItemModifier::class);
    }

    public function getLocalizedName()
    {
        if (!is_array($this->name))
            return '';
        $locale = app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }

    public function getLocalizedDescription()
    {
        if (!is_array($this->description))
            return '';
        $locale = app()->getLocale();
        return $this->description[$locale] ?? ($this->description['en'] ?? '');
    }
}
