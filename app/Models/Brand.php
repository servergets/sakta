<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'client_id',
        'image',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
