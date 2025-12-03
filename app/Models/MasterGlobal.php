<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterGlobal extends Model
{
    protected $fillable = [
        'group',
        'code',
        'name',
        'description',
        'sort_order',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope: Ambil data berdasarkan grup tertentu (misal gender, religion, dll)
     */
    public function scopeGroup($query, string $group)
    {
        return $query->where('group', $group)->where('is_active', true)->orderBy('sort_order');
    }
}
