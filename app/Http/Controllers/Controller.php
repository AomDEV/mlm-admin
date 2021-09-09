<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Carbon\Carbon;
use App\Models\CashWallet;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Transaction;
use App\Models\AdditionalFunction;
use App\Models\CompanyTransaction;
use App\Models\User;
use App\Models\CompanyWallet;
use App\Models\CompanyDeposit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function icon()
    {
        return view('icons-boxicons');
    }

    public function depositCompanyWallaet($userId, $amount, $detail)
    {
        // $userId = Auth::user()->id;
        // $amount = 100000;
        // $detail = 'ทดสอบ';
        // $userCreateId = Auth::user()->id;

        if ($userId == null || $amount == null || $detail == null) {

            $data = [
                'title' => 'ผิดพลาด!',
                'msg' => 'กรุณาใส่ข้อมูลที่จำเป็น',
                'status' => 'warning',
            ];

            return $data;
        }
        if ($amount <= 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'จำนวนเงินไม่ถูกต้อง',
                'status' => 'error',
            ];

            return $data;
        }

        $wallet = CompanyWallet::where('user_id', 1,)->first();

        DB::beginTransaction();

        if ($wallet == null) {
            $wallet = new CompanyWallet;
            $wallet->user_id = 1;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        $deposit = new CompanyDeposit;
        $deposit->user_id = $userId;
        $deposit->amount = (string) $amount < 0 ? $amount * (-1) : $amount;
        $deposit->transaction_timestamp = Carbon::now();
        $deposit->detail = $detail ? $detail : 'เติมเงินเข้า COMPANY - WALLET';
        $deposit->save();

        $tmpAmount = $amount < 0 ? $amount * (-1) : $amount;
        $oldBalance = $wallet->balance;
        $oldDeposit = $wallet->deposit;
        $newBalance = $oldBalance + $tmpAmount;

        $ts = new CompanyTransaction;
        $ts->user_id = $userId;
        $ts->amount = (string) $amount;
        $ts->balance = (string) $newBalance;
        $ts->type = 'DEPOSIT';
        $ts->transaction_timestamp = Carbon::now();
        $ts->detail = $detail ? $detail : 'เติมเงินเข้า COMPANY-WALLET';
        $ts->save();

        $newDeposit = $oldDeposit + $amount;
        $wallet->balance = (string) $newBalance;
        $wallet->deposit = (string) $newDeposit;
        $wallet->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'สร้างรายการเติมเงินสำเร็จ',
            'status' => 'success',
            'amount' => $amount,
        ];

        return $data;
    }
}
