<?php
// app/Models/Project.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Project extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'name',
        'description',
        'project_photo',
        'product_id',
        'qty',
        'price',
        'brand_id',
        'client_id',
        'start_date',
        'end_date',
        'max_buyer',
        'min_purchase',
        'margin_sakta',
        'margin_sales',
        'margin_buyer',
        'status',
        'budget',
        'total_purchase'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
        'budget' => 'decimal:2',
        'qty' => 'integer',
        'max_buyer' => 'integer',
        'min_purchase' => 'integer',
        'margin_sakta' => 'integer',
        'margin_sales' => 'integer',
        'margin_buyer' => 'integer',
        'status' => 'string',
    ];

    protected static function booted()
    {
        static::saving(function ($project) {
            $project->total_purchase = $project->qty * $project->price;
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function transactions(): HasMany
    {
        // return $this->hasMany(Transaction::class);
        return $this->hasMany(ProjectTransaction::class, 'project_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Check if project is active
     */
    public function getIsActiveAttribute(): bool
    {
        $today = now()->format('Y-m-d');
        return $this->status === 'active' && 
               $this->start_date <= $today && 
               ($this->end_date === null || $this->end_date >= $today);
    }

    public function statusRelation(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'status');
    }
    /**
     * Check if project is completed
     */
    // public function getIsCompletedAttribute(): bool
    // {
    //     return $this->status === 'completed' || 
    //            ($this->end_date !== null && $this->end_date < now()->format('Y-m-d'));
    // }

    /**
     * Check if project is planning
     */
    // public function getIsPlanningAttribute(): bool
    // {
    //     return $this->status === 'planning';
    // }

    /**
     * Check if project is cancelled
     */
    // public function getIsCancelledAttribute(): bool
    // {
    //     return $this->status === 'cancelled';
    // }

    /**
     * Scope active projects
     */
    // public function scopeActive($query)
    // {
    //     $today = now()->format('Y-m-d');
    //     return $query->where('status', 'active')
    //                 ->where('start_date', '<=', $today)
    //                 ->where(function($q) use ($today) {
    //                     $q->whereNull('end_date')
    //                       ->orWhere('end_date', '>=', $today);
    //                 });
    // }

    /**
     * Scope completed projects
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 54)
                    ->orWhere(function($q) {
                        $q->whereNotNull('end_date')
                          ->where('end_date', '<', now()->format('Y-m-d'));
                    });
    }

    /**
     * Scope upcoming projects
     */
    // public function scopeUpcoming($query)
    // {
    //     return $query->where('status', 'planning')
    //                 ->orWhere('start_date', '>', now()->format('Y-m-d'));
    // }

    /**
     * Scope planning projects
     */
    // public function scopePlanning($query)
    // {
    //     return $query->where('status', 'planning');
    // }

    /**
     * Scope cancelled projects
     */
    // public function scopeCancelled($query)
    // {
    //     return $query->where('status', 'cancelled');
    // }
    
}