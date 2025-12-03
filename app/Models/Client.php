<?php
// app/Models/Client.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'email',
        'phone',
        'address',
        'pic_name',
        'pic_phone',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function estimations(): HasMany
    {
        return $this->hasMany(Estimation::class);
    }
}