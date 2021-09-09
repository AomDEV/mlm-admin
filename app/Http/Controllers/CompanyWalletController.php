<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompanyWallet;
use App\Models\CompanyTransaction;
use Illuminate\Support\Facades\View;


class CompanyWalletController extends Controller
{
      public function index()
    {

        // $userId = Auth::user()->id;

        $wallet = CompanyWallet::where('user_id', 1)->first();

        if ($wallet == null) {
            $wallet = new CompanyWallet;
            $wallet->user_id = 1;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('company-wallet.index', compact('wallet') );
    }

    public function search(Request $req)
    {

        // return $req->all();
        $from = $req->from != null && $req->from != 'Invalid date' ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null && $req->to != 'Invalid date' ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;

        // return $from;

        $wallet = CompanyWallet::where('user_id', 1)->first();

        if ($type == 'all') {
            if ($from != null && $to != null) {
                $data = CompanyTransaction::with('user')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();
                // return $data;

            } else if ($from != null && $to == null) {

                $data = CompanyTransaction::with('user')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->orderBy('id', 'desc')->get();

            } else if ($from == null && $to != null) {

                $data = CompanyTransaction::with('user')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();
            } else {

                $data = CompanyTransaction::with('user')
                    ->orderBy('id', 'desc')->get();
            }
        } else if ($type == 'in') {
            if ($from != null && $to != null) {
                $data = CompanyTransaction::with('user')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type',  'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();

            } else if ($from != null && $to == null) {
                $data = CompanyTransaction::with('user')
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();

            } else if ($from == null && $to != null) {
                $data = CompanyTransaction::with('user')
                    ->where('type', 'like', '%DEPOSIT%')
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->orderBy('id', 'desc')->get();
            } else {
                $data = CompanyTransaction::with('user')
                    ->where('type', 'like', '%DEPOSIT%')
                    ->orderBy('id', 'desc')->get();
            }
        } else if ($type == 'out') {
            if ($from != null && $to != null) {
                $data = CompanyTransaction::with('user')
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type', 'like', '%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

            } else if ($from != null && $to == null) {
                $data = CompanyTransaction::with('user')
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '>=', $from)
                    ->where('type','like', '%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

            } else if ($from == null && $to != null) {
                $data = CompanyTransaction::with('user')
                    // ->orWhere('to_user_id', $userId)
                    ->whereDate('transaction_timestamp', '<=', $to)
                    ->where('type','like', '%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();

            } else {
                $data = CompanyTransaction::with('user')
                    ->where('type', 'like' ,'%WITHDRAW%')
                    ->orderBy('id', 'desc')->get();
            }
        } else {
        }



        $i = 1;

        $view = View::make('company-wallet.view-make.transaction-table', compact('data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);
    }
}
