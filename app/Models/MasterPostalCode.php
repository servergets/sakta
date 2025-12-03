<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPostalCode extends Model
{
    use HasFactory;

    protected $table = 'master_postal_codes';

    protected $fillable = [
        'postal_code',
        'subdistrict_id',
        'district_id',
        'city_id',
        'province_id',
    ];

    /**
     * Relasi ke kelurahan
     */
    public function subdistrict()
    {
        return $this->belongsTo(MasterSubdistrict::class, 'subdistrict_id');
    }

    /**
     * Relasi ke kecamatan
     */
    public function district()
    {
        return $this->belongsTo(MasterDistrict::class, 'district_id');
    }

    /**
     * Relasi ke kota
     */
    public function city()
    {
        return $this->belongsTo(MasterCity::class, 'city_id');
    }

    /**
     * Relasi ke provinsi
     */
    public function province()
    {
        return $this->belongsTo(MasterProvince::class, 'province_id');
    }
}
