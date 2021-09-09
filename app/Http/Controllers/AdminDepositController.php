<?php

namespace App\Http\Controllers;

use App\Models\CashWallet;
use Illuminate\Http\Request;
use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AdminDepositController extends Controller
{

    public function index()
    {
        return view('admin-deposit.index');
    }


    public function show()
    {
        return datatables()->of(
            Deposit::query()->with('user', 'approveUser', 'cancleUser')->where('slip_img', '!=', null)->orderBy('created_at', 'desc')
        )->toJson();
    }

    public function store(Request $req)
    {
        $status = $req->status;
        $depositId = $req->id;
        $note = $req->note;

        $deposit = Deposit::find($depositId);

        if($deposit == null || $deposit->status != 0){
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'มีบางอย่างผิดพลาด กรุณาติดต่อ Admin',
                'status' => 'error',
            ];
            return $data;
        }
        DB::beginTransaction();

        if($status == 1){
                $wallet = CashWallet::where('user_id', $deposit->user_id)->first();
                $oldBalance = $wallet->balance;
                $oldDeposit= $wallet->deposit;
                $newBalance = $oldBalance + $deposit->amount;

                // $code = $this->getCodeForCash();

                $ts = new Transaction;
                $ts->user_id = $deposit->user_id;
                $ts->amount = (string) $deposit->amount;
                $ts->balance = (string) $newBalance;
                $ts->type = 'DEPOSIT';
                $ts->transaction_timestamp = Carbon::now();
                $ts->detail = $deposit->detail ? $deposit->detail : 'เติมเงินเข้า CASH-WALLET ของฉัน';
                $ts->user_create_id = $deposit->user_create_id;
                $ts->user_approve_id = Auth::user()->id;
                $ts->code = $deposit->code;
                $ts->deposit_id = $deposit->id;
                $ts->save();

                $newDeposit = $oldDeposit + $deposit->amount;
                $wallet->balance = (string) $newBalance;
                $wallet->deposit = (string) $newDeposit;
                $wallet->save();

                $deposit->status = 1;
                $deposit->user_approve_id = Auth::user()->id;
                $deposit->approved_at = Carbon::now();
                $deposit->save();


            $data = [
                    'title' => 'สำเร็จ!',
                    'msg' => 'เติมเงินสำเร็จ',
                    'status' => 'success',
                ];


        }else{
            if ($note == null) {
                $data = [
                    'title' => 'ไม่สำเร็จ!',
                    'msg' => 'กรุณากรอกเหตุผล',
                    'status' => 'error',
                ];
                return $data;
            }
                $deposit->note = $note;
                $deposit->status = 2;
                $deposit->user_cancle_id = Auth::user()->id;
                $deposit->approved_at = Carbon::now();
                $deposit->save();


            $data = [
                'title' => 'สำเร็จ!',
                'msg' => 'ยกเลิกการเติมเงินสำเร็จ',
                'status' => 'success',
            ];
        }



        DB::commit();




        return $data;
    }


    public function countWaitDeposit()
    {
       $count =  Deposit::where('status', 0)->count();

       return $count;
    }



    public function getCodeForCash()
    {
        $now_at = Carbon::now();

        $month = $now_at->month;

        $day = $now_at->day;

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }

        if (strlen($day) == 1) {
            $day = '0' . $day;
        }

        $year = substr($now_at->year, -2);

        $search_code =  'A' . $year . $month . $day;

        // return $search_code;

        $lastest_code1 = Deposit::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();
        $lastest_code2 = Withdraw::where('code', 'LIKE', $search_code . '%')->orderBy('code', 'desc')->first();

        $rand = rand(1, 9);
        if ($lastest_code1 == null && $lastest_code2 == null) {
            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        if ($lastest_code1 != null && $lastest_code2 != null) {
            $code1 = substr($lastest_code1->code, 0, -1);;
            $num1 = (int) substr($code1, -3);

            $code2 = substr($lastest_code2->code, 0, -1);;
            $num2 = (int) substr($code2, -3);

            if ($num1 > $num2) {
                $code = $lastest_code1->code;
            } else {
                $code = $lastest_code2->code;
            }
        } else if ($lastest_code1 != null && $lastest_code2 == null) {
            $code1 = substr($lastest_code1->code, 0, -1);;
            $num1 = (int) substr($code1, -3);
            $code = $lastest_code1->code;
        } else if ($lastest_code1 == null && $lastest_code2 != null) {
            $code2 = substr($lastest_code2->code, 0, -1);;
            $num1 = (int) substr($code2, -3);
            $code = $lastest_code2->code;
        } else {

            $current_code = $search_code . '0001' . $rand;

            return $current_code;
        }

        // $code = $lastest_code->code;

        $code = substr($code, 0, -1);;
        // return $code;

        $num = (int) substr($code, -3);
        $code = $num + 1;
        $count = 4 - strlen($code);

        for ($i = 0; $i < $count; $i++) {
            $code = '0' . $code;
        }

        $current_code = $search_code . $code . $rand;

        return $current_code;
    }


}
