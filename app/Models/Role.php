<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function scopePermissions($query){

        return $query->where("is_active", 1);

    }
}
