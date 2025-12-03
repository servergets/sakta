<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Buyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_photo',
        'buyer_name',
        'citizenship',
        'birth_date',
        'gender_id',
        'religion_id',
        'id_number',
        'tax_number',
        'phone',
        'company_name',
        'email',
        'account_holder_name',
        'bank_id',
        'account_number',
        'home_address',
        'home_country_id',
        'home_province_id',
        'home_city_id',
        'home_postal_code',
        'home_district_id',
        'home_subdistrict_id',
        'office_address',
        'office_country_id',
        'office_province_id',
        'office_city_id',
        'office_postal_code',
        'office_district_id',
        'office_subdistrict_id',
        'id_card_file',
        'tax_card_file',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'birth_date' => 'date',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
    
    public function gender(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'gender_id')->where('group', 'gender');
    }

    public function religion(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'religion_id')->where('group', 'religion')->orderBy('sort_order');
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'bank_id')->where('group', 'bank');
    }

    public function homeCountry(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'home_country_id')->where('group', 'country');
    }

    public function homeProvince(): BelongsTo
    {
        return $this->belongsTo(MasterProvince::class, 'home_province_id');
    }

    public function homeCity(): BelongsTo
    {
        return $this->belongsTo(MasterCity::class, 'home_city_id');
    }

    public function officeCountry(): BelongsTo
    {
        return $this->belongsTo(MasterGlobal::class, 'office_country_id')->where('group', 'country');
    }

    public function officeProvince(): BelongsTo
    {
        return $this->belongsTo(MasterProvince::class, 'office_province_id');
    }

    public function officeCity(): BelongsTo
    {
        return $this->belongsTo(MasterCity::class, 'office_city_id');
    }
}