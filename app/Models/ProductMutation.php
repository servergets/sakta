<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMutation extends Model
{
    use HasFactory;

    protected $table = 'product_mutations';

    protected $fillable = [
        'product_id',
        'mutation_type_id',
        'mutation_date',
        'reference_code',
        'description',
        'qty_in',
        'qty_out',
        'unit_price',
        'total_value',
        'current_stock',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'mutation_date' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // 🔗 Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔗 Relasi ke master_globals (jenis mutasi)
    public function mutationType()
    {
        return $this->belongsTo(MasterGlobal::class, 'mutation_type_id');
    }

    // 🔗 Relasi ke user pembuat (jika ada model User)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    protected static function booted()
    {
        static::creating(function ($mutation) {
            // Ambil tahun sekarang
            $year = now()->format('Y');

            // Ambil mutasi terakhir di tahun ini
            $lastCode = self::whereYear('mutation_date', $year)
                ->where('reference_code', 'like', "PO/%/$year%")
                ->orderBy('id', 'desc')
                ->value('reference_code');

            // Tentukan nomor urut baru
            $nextNumber = 1;

            if ($lastCode) {
                $parts = explode('/', $lastCode);
                if (isset($parts[2]) && is_numeric($parts[2])) {
                    $nextNumber = intval($parts[2]) + 1;
                }
            }

            // Format kode baru: PO/ddmmyyyy/0001
            $mutation->reference_code = sprintf(
                'PO/%s/%04d',
                now()->format('dmY'),
                $nextNumber
            );

            // Set created_by otomatis
            if (auth()->check()) {
                $mutation->created_by = auth()->id();
            }
        });

        static::updating(function ($mutation) {
            if (auth()->check()) {
                $mutation->updated_by = auth()->id();
            }
        });
    }

}
