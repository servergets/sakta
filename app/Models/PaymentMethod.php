<?php
// app/Models/PaymentMethod.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'account_name',
        'account_no',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}