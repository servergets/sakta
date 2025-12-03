<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterDistrict extends Model
{
    use HasFactory;

    protected $table = 'master_districts';

    protected $fillable = [
        'district_name',
        'city_id',
    ];

    /**
     * Relasi ke kota
     */
    public function city()
    {
        return $this->belongsTo(MasterCity::class, 'city_id');
    }

    /**
     * Relasi ke kelurahan
     */
    public function subdistricts()
    {
        return $this->hasMany(MasterSubdistrict::class, 'district_id');
    }

    /**
     * Relasi ke kode pos
     */
    public function postalCodes()
    {
        return $this->hasMany(MasterPostalCode::class, 'district_id');
    }
}
