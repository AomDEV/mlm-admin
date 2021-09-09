<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDeposit extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
