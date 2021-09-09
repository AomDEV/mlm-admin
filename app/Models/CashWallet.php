<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashWallet extends Model
{
    use HasFactory;
    
    protected $table = 'cash_wallets';

    protected $fillable = [
        'id',
        'user_id',
        'balance',
    ];
}
