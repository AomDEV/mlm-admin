<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinWallet extends Model
{
    use HasFactory;

    protected $table = 'coin_wallets';

    protected $fillable = [
        'id',
        'user_id',
        'balance',
    ];
}
