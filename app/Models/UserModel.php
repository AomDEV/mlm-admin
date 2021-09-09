<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'prefix_name',
        'fullname',
        'username',
        'firstname',
        'lastname',
        'on_card',
        'dob',
        'email',
        'phone_number',
        'line',
        'fb',
        'address',
        'zip_code',
        'send_address',
        'user_invite_id',
        'user_upline_id',
        'bank_id',
        'bank_no',
        'bank_own_name',
        'level',
        'avatar',
        'position_space',
        'thai_id',
        'birth_date',
        'nationality',
        'sex',
        'ig',
        'province',
        'district',
        'sub_district',
        'send_province',
        'send_sub_district',
        'send_district',
        'send_zip_code',
        'send_email',
        'send_phone_number',
        'password',
        'product_id',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function childrenUpline(){
        return $this->hasMany(UserModel::class, 'user_upline_id', 'id')
        ->orderBy('position_space','ASC')
        ->with('childrenUpline');
    }

    public function getChlidrenAttribute(){
        return $this->hasMany(UserModel::class, 'user_upline_id', 'id')
        ->orderBy('position_space','ASC')
        ->with('childrenUpline');
    }

    public function product(){
        return $this->belongsTo(ProductModel::class, 'product_id', 'id')->withDefault();
    }

    public function wallet(){
        return $this->belongsTo(CashWallet::class, 'id', 'user_id');
    }

    public function cashWallet()
    {
        return $this->belongsTo(CashWallet::class, 'id', 'user_id');
    }

    public function coinWallet()
    {
        return $this->belongsTo(CoinWallet::class, 'id', 'user_id');
    }

    public function inviteCount()
    {
        return UserModel::where('user_invite_id', $this->id)->count();
    }

}
