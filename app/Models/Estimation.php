<?php
// app/Models/Estimation.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Estimation extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'estimation_long',
        'client_id',
        'project_id',
        'estimation_date',
        'valid_until',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'estimation_date' => 'date',
        'valid_until' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function estimationItems(): HasMany
    {
        return $this->hasMany(EstimationItem::class);
    }

    protected static function booted()
    {
        static::creating(function ($estimation) {
            if (empty($estimation->code)) {
                $estimation->code = 'EST-' . date('Ymd') . '-' . str_pad(static::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}