<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterSubdistrict extends Model
{
    use HasFactory;

    protected $table = 'master_subdistricts';

    protected $fillable = [
        'subdistrict_name',
        'district_id',
    ];

    /**
     * Relasi ke kecamatan
     */
    public function district()
    {
        return $this->belongsTo(MasterDistrict::class, 'district_id');
    }

    /**
     * Relasi ke kode pos
     */
    public function postalCodes()
    {
        return $this->hasMany(MasterPostalCode::class, 'subdistrict_id');
    }
}
