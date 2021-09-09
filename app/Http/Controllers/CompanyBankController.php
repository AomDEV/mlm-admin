<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\AdditionalFunction;
use App\Models\CompanyBankAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyBankController extends Controller
{
    public function index()
    {

        $bank = CompanyBankAccount::orderBy('id', 'desc')->first();
        return view('company-bank.index', compact('bank'));
    }


    public function store(Request $req)
    {
        // return $req->all();


        $bank_name = $req->bank_name;
        $bank_account_name = $req->bank_account_name;
        $bank_account_no = $req->bank_account_no;
        $bank_branch = $req->bank_branch;

        $bank = CompanyBankAccount::orderBy('id', 'desc')->first();
        if ($bank == null) {
            $bank = new CompanyBankAccount;
        }

        DB::beginTransaction();

        $bank->bank_name = $bank_name;
        $bank->bank_account_name = $bank_account_name;
        $bank->bank_account_no = $bank_account_no;
        $bank->bank_branch = $bank_branch;
        $bank->save();

        DB::commit();



        $data = [
            'title' => 'บันทึกสำเร็จ!',
            'msg' => 'บันทึกข้อมูลธนาคารสำเร็จ',
            'status' => 'success',
        ];
        return $data;
    }


}
