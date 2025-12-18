<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModifierOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'modifier_id',
        'name',
        'price_diff',
    ];

    protected $casts = [
        'name' => 'array',
        'price_diff' => 'decimal:3',
    ];

    public function modifier()
    {
        return $this->belongsTo(ItemModifier::class, 'modifier_id');
    }

    public function getLocalizedName()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
