<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NavigationItem extends Model
{
    protected $fillable = [
        'navigation_group_id',
        'parent_id',
        'name',
        'label',
        'icon',
        'url',
        'resource',
        'sort',
        'is_active',
        'is_group',
        'is_visible',
        'badge',
        'badge_color',
        'permissions',
        'roles',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_group' => 'boolean',
        'is_visible' => 'boolean',
        'permissions' => 'array',
        'roles' => 'array',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(NavigationGroup::class, 'navigation_group_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(NavigationItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(NavigationItem::class, 'parent_id')->orderBy('sort');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('is_visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort');
    }

    public function scopeWithoutGroup($query)
    {
        return $query->whereNull('navigation_group_id');
    }

    public function scopeRootItems($query)
    {
        return $query->whereNull('parent_id');
    }

    // Check if user has permission to see this item
    public function canView(?array $userRoles = null): bool
    { 
        if (!$this->is_active || !$this->is_visible) {
            return false;
        }

        // If no roles defined, is_visible to all
        if (empty($this->roles)) {
            return true;
        }

        // Check if user has any of the required roles
        if ($userRoles) { 
            return !empty(array_intersect($this->roles, $userRoles));
        }

        return true;
    }
}