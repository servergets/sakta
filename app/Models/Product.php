<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Product extends Model
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'code',
        'name',
        'description',
        'product_type_id',
        'product_unit_id',
        'brand_id',
        'price',
        'price_selling',
        'ppn',
        'image',
        'stock',
        'minimum_stock',
        'is_active',
        'client_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'price_selling' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function productUnit(): BelongsTo
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function estimationItems(): HasMany
    {
        return $this->hasMany(EstimationItem::class);
    }
    
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function mutations()
    {
        return $this->hasMany(ProductMutation::class);
    }

}
