<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterProvince extends Model
{
    use HasFactory;

    protected $table = 'master_provinces';

    protected $fillable = [
        'province_name',
    ];

    /**
     * Relasi ke kota
     */
    public function cities()
    {
        return $this->hasMany(MasterCity::class, 'province_id');
    }

    /**
     * Relasi ke kode pos
     */
    public function postalCodes()
    {
        return $this->hasMany(MasterPostalCode::class, 'province_id');
    }
}
