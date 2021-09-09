<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinTransaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
    /*
    public function fromUser()
    {
        return $this->belongsTo(UserModel::class, 'from_user_id', 'id');
    }

    public function toUser()
    {
        return $this->belongsTo(UserModel::class, 'to_user_id', 'id');
    }

    public function fromCoinWallet()
    {
        return $this->belongsTo(CashWallet::class, 'from_coin_wallet_id', 'id');
    }

    public function toCoinWallet()
    {
        return $this->belongsTo(CashWallet::class, 'form_coin_wallet_id', 'id');
    }
    */


    public function withdraw()
    {
        return $this->belongsTo(CoinWithdraw::class, 'withdraw_id', 'id');
    }

    public function deposit()
    {
        return $this->belongsTo(CoinDeposit::class, 'deposit_id', 'id');
    }
}
