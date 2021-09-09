<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdditionalFunction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class AdditionalFunctionController extends Controller
{
    public function index()
    {
        return view('additional-function.index');
    }

    public function show()
    {
        // return Package::query()->orderBy('updated_at', 'desc')->get()->toJson();

        return datatables()->of(
            AdditionalFunction::query()->orderBy('id', 'asc')
        )->toJson();
    }

    public function store(Request $req)
    {
        // return $req->all();

        $id = $req->id;
        $code = $req->code;
        $name = $req->name;

        $func = null;

        if($id != null){
            $func = AdditionalFunction::find($id);
        }

        if ($func == null) {
            $func = new AdditionalFunction;
            $func->active = 1;
        }

        DB::beginTransaction();

        $func->code = $code;
        $func->name = $name;
        $func->save();

        DB::commit();

        $data = [
            'title' => 'บันทึกสำเร็จ!',
            'msg' => 'บันทึก Function สำเร็จ',
            'status' => 'success',
        ];
        return $data;
    }

    public function changeStatus(Request $req)
    {

        $id = $req->id;

        DB::beginTransaction();

        $active = 0;
        $func = AdditionalFunction::find($id);
        $oldStatus = $func->active;

        if ($oldStatus == 1) {
            $active = 0;
        } else {
            $active = 1;
        }
        $func->active = $active;
        $func->save();
        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'แก้ไขสำสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }

    public function delete(Request $req)
    {

        $id = $req->id;

        DB::beginTransaction();

        $func = AdditionalFunction::find($id);

        if ($func == null) {
            $data = [
                'title' => 'ไม่สำเร็จ!',
                'msg' => 'ไม่พบรายการ',
                'status' => 'error',
            ];

            return $data;
        }

        AdditionalFunction::find($id)->delete();


        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'ลบ Function สำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }
}
