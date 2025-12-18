<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'name',
        'price_diff',
        'is_default',
    ];

    protected $casts = [
        'name' => 'array',
        'price_diff' => 'decimal:3',
        'is_default' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getLocalizedName()
    {
        $locale = app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
