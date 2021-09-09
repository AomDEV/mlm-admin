<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use App\Models\ProductModel;
use App\Models\BankModel;
use App\Models\CashWallet;
use App\Models\CoinWallet;
use App\Models\Transaction;
use App\Models\CashTransaction;
use App\Models\CoinTransaction;
use App\Http\Controllers\MLM\IndexController;

use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function userList()
    {
        $userList = UserModel::orderBy('updated_at', 'desc')->get();
        return view("manage.user",['userList'=> $userList]);
    }

    public function getUserList(Request $request)
    {

        $userList = UserModel::orderBy('updated_at', 'desc')->get();

        $dataList = [];

        foreach($userList as $user){

            $cash = CashWallet::where('user_id', $user->id)->first();
            $coin = CoinWallet::where('user_id', $user->id)->first();

            $dataList[] = [

                "create_date" => date('d-m-Y', strtotime($user->created_at)),
                "id" => $user->id,
                "fullname" => $user->firstname . " " . $user->lastname,
                "invite" => $user->user_invite_id,
                "upline" => $user->user_upline_id,
                "invite_count" => ($user->inviteCount() ?? 0) . ' ท่าน',
                "cash_balance" =>  $cash->balance ?? 0.00,
                "coin_balance" => $coin->balance ?? 0.00,
                "phone_number" => $user->phone_number,
                "email" => $user->email,
                "line" => $user->line,
            ];
        }

        $dataListCollect = collect($dataList);

        return datatables()->of($dataListCollect)->toJson();

    }

    public function viewUser($id){

        $userData = UserModel::find($id);
        if($userData == null){
            return redirect()->route('userList');
        }
        return view("manage.viewuser",['userData'=> $userData]);
    }

    public function userUpdate(Request $request){

        $validator = Validator::make($request->all(), [

            'zip_code'=>'numeric|digits:5',
            'phone_number'=>'required|numeric|digits:10',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'isSuccess' => false,
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }

        $userData = UserModel::find($request->get('user_id'));

        if($userData){

            $form_user = array(
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'prefix_name' => $request->prefix_name,
                'nationality' => $request->nationality,
                'sex' => $request->sex,
                'line' => $request->line,
                'fb' => $request->fb,
                'ig' => $request->ig,

                'address' => $request->address,
                'province' => $request->province,
                'sub_district' => $request->sub_district,
                'district' => $request->district,
                'zip_code' => $request->zip_code,
                'email' => $request->email,
                'phone_number' => $request->phone_number,

                'send_address' => $request->send_address,
                'send_province' => $request->send_province,
                'send_sub_district' => $request->send_sub_district,
                'send_district' => $request->send_district,
                'send_zip_code' => $request->send_zip_code,
                'send_email' => $request->send_email,
                'send_phone_number' => $request->send_phone_number,
            );


            if($userData->update($form_user)){
                return response()->json([
                    'isSuccess' => true,
                    'status'=>200,
                    'Message'=>'บันทึกการแก้ไขเรียบร้อย'
                ]);
            }else{
                return response()->json([
                    'isSuccess' => false,
                    'status'=>500,
                    'Message'=>'เกิดข้อผิดพลาดโปรดลองใหม่อีกครั้ง'
                ]);
            }

        }else
        {
            return response()->json([
                'isSuccess' => false,
                'status'=>404,
                'Message'=>'Not Found.'
            ]);
        }
    }

    public function addUserIndex()
    {
        $products = ProductModel::orderBy('id', 'asc')->get();
        $banks = BankModel::where('active', true)->orderBy('order', 'asc')->get();

        return view("manage.adduser",['products'=> $products,'banks'=>$banks]);
    }

    public function addUserWithParam($upline_id, $position){

        $products = ProductModel::orderBy('id', 'asc')->get();
        $banks = BankModel::where('active', true)->orderBy('order', 'asc')->get();
        $user_upline = UserModel::findOrFail($upline_id);

        return view("manage.adduserparam",['products'=> $products,'banks'=>$banks, 'user_upline'=>$user_upline]);
    }

    public function createUser(Request $request, IndexController $indexController){
        $validator = Validator::make($request->all(), [
            'user_invite_id' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'numeric', 'digits:10', 'unique:users'],
            'password' => ['min:6', 'required_with:password_confirmation', 'same:password_confirmation'],
            'password_confirmation' => ['min:6']
        ]);

        if (!$validator->fails()) {
            $user = new UserModel();
            $user = $user->fill($request->all());
            $user->password = Hash::make($request->input('password'));
            $user->username = $request->input('phone_number');
            $user->send_email = $request->input('email');
            $user->send_phone_number = $request->input('phone_number');
            $user->avatar = '/assets/images/users/avatar.jpg';
            $user->first_time_login = 'true';
            // $user->product_id = $product_id;
            if($user->save()){
                $indexController->CreateNewUser($user->user_upline_id,$user->user_invite_id,$user->id);
                $this->createCashWallet($user->id);
                $this->createCoinWallet($user->id);
                return redirect()->route('userList');
            }
        }

        return back()
            ->withErrors($validator)
            ->withInput();
    }

    public function checkUserFindInvite(Request $request){
        $user = UserModel::where('id', $request->input('invite_id'))->first();
        if($user){
            return $user->firstname.' '.$user->lastname;
        }
        return 'ไม่พบข้อมูล';
    }

    public function checkUserFindUpline(Request $request){

        $user = UserModel::where('id', $request->input('upline_id'))->first();

        if($user){

            $leftStatus = true;
            $rightStatus = true;

            $avaliable = UserModel::where('user_upline_id', $user->id)->get();

            foreach($avaliable as $currentuser) {

                if($currentuser->position_space == "left"){

                    $leftStatus = false;
                }else if ($currentuser->position_space == "right") {

                    $rightStatus = false;
                }
            }

            if ($leftStatus == false && $rightStatus == false){
                return response()->json([
                    'isSuccess' => false,
                    'status'=>200,
                    'upline_name' => $user->firstname.' '.$user->lastname,
                    'message' => 'อัพไลน์ '.$user->firstname.' '.$user->lastname.' ไม่มีตำแหน่งว่าง',
                    'left'=> $leftStatus,
                    'right'=> $rightStatus,
                ]);

            }else{
                return response()->json([
                    'isSuccess' => true,
                    'status'=>200,
                    'upline_name' => $user->firstname.' '.$user->lastname,
                    'message' => 'พบตำแหน่งว่าง',
                    'left'=> $leftStatus,
                    'right'=> $rightStatus,
                ]);
            }
        }else{

            return response()->json([
                'isSuccess' => false,
                'status'=>404,
                'upline_name' => 'ไม่พบข้อมูล',
                'message' => 'ไม่พบข้อมูลอัพไลน์นี้',
                'left'=> false,
                'right'=> false,
            ]);
        }


    }

    public function createCashWallet($userId)
    {
        $cashWallet = CashWallet::where('user_id', $userId)->first();

        if ($cashWallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return true;
    }

    public function createCoinWallet($userId)
    {

        $wallet = CoinWallet::where('user_id', $userId)->first();

        if ($wallet == null) {
            $wallet = new CoinWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return true;
    }

    public function cashHistoryIndex()
    {
        return view('wallet-history.cash-index');
    }

    public function coinHistoryIndex()
    {
        return view('wallet-history.coin-index');
    }

    public function cashHistory($id)
    {

        $userId = $id;

        if ($userId == null) {
            return abort(404);
        }
        $userData = UserModel::find($userId);
        $wallet = CashWallet::where('user_id', $userId)->first();

        if ($wallet == null) {
            $wallet = new CashWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('wallet-history.cash', compact('wallet','userData') );
    }

    public function coinHistory($id)
    {

        $userId = $id;

        if ($userId == null) {
            return abort(404);
        }
        $userData = UserModel::find($userId);
        $wallet = CoinWallet::where('user_id', $userId)->first();

        if ($wallet == null) {
            $wallet = new CoinWallet;
            $wallet->user_id = $userId;
            $wallet->balance = 0;
            $wallet->deposit = 0;
            $wallet->withdraw = 0;
            $wallet->save();
        }

        return view('wallet-history.coin', compact('wallet','userData'));
    }

    public function cashHistorySearch(Request $req)
    {

        // return $req->all();
        $from = $req->from != null && $req->from != 'Invalid date' ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null && $req->to != 'Invalid date' ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;
        $code = $req->code;

        // return $from;

        $userId = $req->user_id;
        $wallet = CashWallet::where('user_id', $userId)->first();

        if($code != null ){

            $data = Transaction::with('user', 'deposit', 'withdraw')
                ->where('user_id', $userId)
                ->where('code', 'LIKE', '%' . $code . '%')
                // ->orderBy('id', 'desc')
                ->get();

        }else{
            if ($type == 'all') {
                if ($from != null && $to != null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                    // return $data;

                } else if ($from != null && $to == null) {

                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();

                } else if ($from == null && $to != null) {

                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {

                    $data = Transaction::with('user')
                        ->where('user_id', $userId)
                        ->orderBy('id', 'desc')
                        ->get();
                }
            } else if ($type == 'in') {
                if ($from != null && $to != null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type',  'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from != null && $to == null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from == null && $to != null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                }
            } else if ($type == 'out') {
                if ($from != null && $to != null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from != null && $to == null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from == null && $to != null) {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {
                    $data = Transaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                }
            } else {
            }
        }





        $i = 1;

        $view = View::make('wallet-history.transaction-table', compact('data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);
    }

    public function coinHistorySearch(Request $req)
    {

        // return $req->all();
        $from = $req->from != null && $req->from != 'Invalid date' ? date('Y-m-d', strtotime($req->from)) : null;
        $to = $req->to != null && $req->to != 'Invalid date' ? date('Y-m-d', strtotime($req->to)) : null;
        $type = $req->type;
        $code = $req->code;
        // return $from;

        $userId = $req->user_id;
        $wallet = CoinWallet::where('user_id', $userId)->first();

        if($code != null){

            $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                ->where('user_id', $userId)
                ->where('code', 'LIKE', '%'.$code.'%')
                ->get();

        }else{
            if ($type == 'all') {
                if ($from != null && $to != null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                    // return $data;

                } else if ($from != null && $to == null) {

                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from == null && $to != null) {

                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {

                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                }
            } else if ($type == 'in') {
                if ($from != null && $to != null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from != null && $to == null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from == null && $to != null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        ->where('type', 'like', '%DEPOSIT%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                }
            } else if ($type == 'out') {
                if ($from != null && $to != null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from != null && $to == null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '>=', $from)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else if ($from == null && $to != null) {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->whereDate('transaction_timestamp', '<=', $to)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                } else {
                    $data = CoinTransaction::with('user', 'deposit', 'withdraw')
                        ->where('user_id', $userId)
                        // ->orWhere('to_user_id', $userId)
                        ->where('type', 'like', '%WITHDRAW%')
                        ->orderBy('id', 'desc')->get();
                        // ->orderBy('transaction_timestamp', 'desc')->get();
                }
            } else {
            }
        }

        $i = 1;

        $view = View::make('wallet-history.transaction-table', compact('data', 'i'))->render();
        return response()->json([
            'html' => $view,
        ]);
    }

}


