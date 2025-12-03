<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_transaction_id',
        'po_number',
        'order_date',
        'product_id',
        'brand_id',
        'quantity',
        'total_amount',
        'status',
        'payment_date'
    ];

    protected $casts = [
        'order_date' => 'date',
        'payment_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Relasi ke model ProjectTransaction
     */
    public function projectTransaction(): BelongsTo
    {
        return $this->belongsTo(ProjectTransaction::class);
    }

    /**
     * Relasi ke model Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relasi ke model Brand
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relasi ke model MasterGlobal untuk status
     */
    public function statusRelation(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'status');
    }
}