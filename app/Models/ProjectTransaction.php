<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'project_id',
        'client_id',
        'buyer_id',
        'start_date',
        'estimation',
        'total_amount',
        'total_amount_raw',
        'purchase_percentage',
        'status',
        'payment_proof',
        'payment_method_id',
        'payment_total',
        'payment_account_no'
    ];

    protected $casts = [
        'start_date' => 'date',
        'total_amount' => 'decimal:2',
        'purchase_percentage' => 'decimal:2',
    ];

    /**
     * Relasi ke model Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Relasi ke model Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relasi ke model Buyer
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    /**
     * Relasi ke model MasterGlobal untuk status
     */
    public function statusRelation(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'status');
    }

    /**
     * Relasi ke model PurchaseOrder (satu project transaction bisa memiliki banyak PO)
     */
    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class, 'project_transaction_id');
    }
    
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    protected function setTotalAmountAttribute($value)
    {
        $cleaned = preg_replace('/[^0-9.,]/', '', $value);
        $cleaned = str_replace(',', '.', $cleaned);
        $cleaned = preg_replace('/\.(?=\d{3}(\.|$))/', '', $cleaned);
        
        $this->attributes['total_amount'] = (float) $cleaned;
    }
    
    protected function setStatusAttribute($value)
    {
        $this->attributes['status'] = $value ?: 47;
    }
}