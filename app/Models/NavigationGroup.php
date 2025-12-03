<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavigationGroup extends Model
{
    protected $fillable = [
        'name',
        'label',
        'icon',
        'sort',
        'is_active',
        'collapsible',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'collapsible' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(NavigationItem::class)->orderBy('sort');
    }

    public function activeItems(): HasMany
    {
        return $this->items()->where('is_active', true)->where('is_visible', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort');
    }
}