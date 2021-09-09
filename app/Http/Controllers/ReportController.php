<?php

namespace App\Http\Controllers;

use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Transaction;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    //SUMMARY IN OUT REPORT
    public function summaryInOut()
    {

        return view('report.summary-in-out.index');
    }

    public function showSummaryInOut(Request $req)
    {
        $from = $req->from != null && $req->from != 'Invalid date' ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null && $req->to != 'Invalid date' ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;


        // return $to;

        $i = 1;
        $data = [];

        $users = UserModel::get();

        $sumBalance = 0;
        $sumDeposit = 0;
        $sumWithdraw= 0;

        if($type == null){
            if($from == null && $to == null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like','%DEPOSIT%')
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($from == null && $to != null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
                // return 'OPKKOPKO';
            }else if($from != null && $to == null){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else{
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }
        }else{
            if($type == 'd'){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->whereDate('transaction_timestamp', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($type == 'm'){

                $f = Carbon::now()->startOfMonth();
                $t = Carbon::now()->endOfMonth();

                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '>=', $f)
                        ->whereDate('transaction_timestamp', '<=', $t)
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'WITHDRAW')
                        ->whereDate('transaction_timestamp', '>=', $f)
                        ->whereDate('transaction_timestamp', '<=', $t)
                        ->where('user_id', $user->id)->sum('amount');


                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }else if($type == 'y'){
                foreach ($users as $user) {
                    $deposit = Transaction::where('type', 'like', '%DEPOSIT%')
                        ->whereYear('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');
                    $withdraw = Transaction::where('type', 'like', '%WITHDRAW%')
                        ->whereYear('transaction_timestamp', '=', date('Y-m-d'))
                        ->where('user_id', $user->id)->sum('amount');

                    $balance = $deposit - $withdraw;

                    $tmp = [
                        'user' => $user,
                        'withdraw' => $withdraw,
                        'deposit' => $deposit,
                        'balance' => $balance,
                    ];

                    array_push($data, $tmp);
                    $sumBalance += $balance;
                    $sumDeposit += $deposit;
                    $sumWithdraw += $withdraw;
                }
            }

        }


        $header = [
            'sumBalance' => $sumBalance,
            'sumDeposit' => $sumDeposit,
            'sumWithdraw' => $sumWithdraw,
        ];


        // return $header;
        $view = View::make('report.summary-in-out.datatable', compact('header', 'data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);

    }

    //SUMMARY TRANSACTION

    public function summaryTransaction()
    {
        return view('report.summary-transaction.index');
    }

    public function showSummaryTransaction(Request $req)
    {

        // return $req->all();
        $from = $req->from != null && $req->from != 'Invalid date' ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null && $req->to != 'Invalid date' ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;

        // return $from;

        if ($type == 'all') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user', 'createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else if ($from != null && $to == null) {

                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else if ($from == null && $to != null) {

                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else {

                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            }
        } else if ($type == 'in') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else if ($from != null && $to == null) {
                $data = Transaction::with('user', 'createUser')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else if ($from == null && $to != null) {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->where('type', 'like', '%DEPOSIT%')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];
            }
        } else if ($type == 'out') {
            if ($from != null && $to != null) {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'like', ' %WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $w =  Transaction::where('type','like', '%WITHDRAW%')->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];

            } else if ($from != null && $to == null) {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type', 'like', '%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '>=', $from)->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];
            } else if ($from == null && $to != null) {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'like', '%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', '%DEPOSIT%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                $w =  Transaction::where('type', 'like', '%WITHDRAW%')->whereDate('transaction_timestamp', '<=', $to)->sum('amount');
                // $w =  Transaction::where('type', 'WITHDRAW')->whereDate('transaction_timestamp', '<=', $to))->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];
            } else {
                $data = Transaction::with('user','createUser', 'withdraw', 'deposit')
                    ->where('type', 'like','%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

                $d =  Transaction::where('type', 'like', ' %DEPOSIT%')->sum('amount');
                $w =  Transaction::where('type', 'like', ' %WITHDRAW%')->sum('amount');
                $b = $d + $w;

                $header = [
                    'withdraw' => $w,
                    'deposit' => $d,
                    'balance' => $b,
                ];
            }
        } else {
        }



        $i = 1;

        $view = View::make('report.summary-transaction.datatable', compact('data', 'i', 'header'))->render();
        return response()->json([
            'html' => $view,
        ]);

    }
}
