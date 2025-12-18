<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModifier extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'name',
        'type',
        'is_required',
    ];

    protected $casts = [
        'name' => 'array',
        'is_required' => 'boolean',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function options()
    {
        return $this->hasMany(ItemModifierOption::class, 'modifier_id');
    }

    public function getLocalizedName()
    {
        if (!is_array($this->name))
            return '';
        $locale = app()->getLocale();
        return $this->name[$locale] ?? ($this->name['en'] ?? '');
    }
}
