<?php

namespace App\Http\Controllers;


use App\Models\CashWallet;
use Illuminate\Http\Request;
use App\Models\Withdraw;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\AdditionalFunction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;


class AdminWithdrawController extends Controller
{
    public function index()
    {
        return view('admin-withdraw.index');
    }

    public function show()
    {
        return datatables()->of(
            Withdraw::query()->with('user.cashWallet', 'bank')->where('bank_id', '!=', null)->orderBy('id', 'desc')
        )->toJson();
    }

    public function store(Request $req)
    {
        $status = $req->status;
        $withdrawId = $req->id;
        $note = $req->note;

        $withdraw = Withdraw::find($withdrawId);

        if ($withdraw == null || $withdraw->status != 0) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'มีบางอย่างผิดพลาด กรุณาติดต่อ Admin',
                'status' => 'error',
            ];
            return $data;
        }
        // $w = CashWallet::where('user_id', $withdraw->user_id)->first();

        DB::beginTransaction();

        if ($status == 1) {
            $wallet = CashWallet::where('user_id', $withdraw->user_id)->first();
            $oldBalance = $wallet->balance;
            $oldWithdraw = $wallet->withdraw;
            $newBalance = $oldBalance - $withdraw->amount;

            if($newBalance < 0){
                $data = [
                    'title' => 'ไม่สำเร็จ!',
                    'msg' => 'ยอดเงินใน wallet ไม่เพียงพอ',
                    'status' => 'error',
                ];
                return $data;
            }
            // $code = $this->getCodeForCash();

            $ts = new Transaction;
            $ts->user_id = $withdraw->user_id;
            $ts->amount = (string) $withdraw->amount;
            $ts->balance = (string) $newBalance;
            $ts->type = 'WITHDRAW';
            $ts->transaction_timestamp = Carbon::now();
            $ts->detail = $withdraw->detail ?  $withdraw->detail : 'ถอนเงินออกจาก CASH-WALLET ของฉัน';
            $ts->user_create_id = $withdraw->user_create_id;
            $ts->user_approve_id = Auth::user()->id;
            $ts->code = $withdraw->code;
            $ts->withdraw_id = $withdraw->id;
            $ts->save();

            $newWithdraw = $oldWithdraw + $withdraw->amount;
            $wallet->balance = (string) $newBalance;
            $wallet->withdraw = (string) $newWithdraw;
            $wallet->save();

            $tax = 0;
            $total_amount = $withdraw->amount;
            $detail = 'เปอร์เซ็นจากถอนเงินเข้าบัญชีธนาคาร';

            $calTax = AdditionalFunction::where('code', 'CALTAX')->where('active', 1)->first();
            if ($calTax) {
                $comAmount = $withdraw->amount * 5  / 100;
                $res = $this->depositCompanyWallaet($withdraw->user_id, $comAmount, $detail);
                // dd($res);
                $tax = $comAmount;
                $total_amount = $withdraw->amount - $tax;
            }

            $withdraw->tax = $tax;
            $withdraw->total_amount = $total_amount;
            $withdraw->status = 1;
            $withdraw->user_approve_id = Auth::user()->id;
            $withdraw->approved_at = Carbon::now();
            $withdraw->save();

            $data = [
                'title' => 'สำเร็จ!',
                'msg' => 'เติมเงินสำเร็จ',
                'status' => 'success',
            ];


        } else {
            if($note == null){
                $data = [
                    'title' => 'ไม่สำเร็จ!',
                    'msg' => 'กรุณากรอกเหตุผล',
                    'status' => 'error',
                ];
                return $data;
            }
            $withdraw->note = $note;
            $withdraw->status = 2;
            $withdraw->user_cancle_id = Auth::user()->id;
            $withdraw->approved_at = Carbon::now();
            $withdraw->save();

            $data = [
                'title' => 'สำเร็จ!',
                'msg' => 'ยกเลิกการถอนเงินสำเร็จ',
                'status' => 'success',
            ];

        }


        DB::commit();


        return $data;
    }

    public function countWaitWithdraw()
    {
        $count =  Withdraw::where('status', 0)->count();

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
