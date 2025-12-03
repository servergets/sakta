<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCity extends Model
{
    use HasFactory;

    protected $table = 'master_cities';

    protected $fillable = [
        'city_name',
        'province_id',
    ];

    /**
     * Relasi ke provinsi
     */
    public function province()
    {
        return $this->belongsTo(MasterProvince::class, 'province_id');
    }

    /**
     * Relasi ke kecamatan
     */
    public function districts()
    {
        return $this->hasMany(MasterDistrict::class, 'city_id');
    }

    /**
     * Relasi ke kode pos
     */
    public function postalCodes()
    {
        return $this->hasMany(MasterPostalCode::class, 'city_id');
    }
}
