<?php

namespace App\Http\Controllers;

use App\Models\AdditionalFunction;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    //

    public function index()
    {
        return view('package.index');
    }

    public function show()
    {
        // return Package::query()->orderBy('updated_at', 'desc')->get()->toJson();

        return datatables()->of(
            Product::query()->orderBy('id', 'asc')
        )->toJson();
    }

    public function store(Request $req)
    {
        // return $req->all();
        $validated = $req->validate([
            'name' => 'string|max:40',
            'imageFile' => 'mimes:jpeg,png|max:1024',
        ]);

        $id = $req->id;
        $code = $req->code;
        $level = $req->level;
        $level_value = $req->level_value;
        $name = $req->name;
        $price = $req->price;
        $price_num = $req->price_num;

        $point = $req->point;

        $product = Product::find($id);
        if ($product == null) {
            $data = [
                'title' => 'กรุณาลองใหม่!',
                'msg' => 'มีบางอย่างผิดพลาดกรุณาติดต่อ Admin',
                'status' => 'error',
            ];
            return $data;
        }

        DB::beginTransaction();

        $path= '';
        if ($req->hasFile('imageFile')) {
            $imageName = time() . '.' . $req->imageFile->extension();
            $req->imageFile->move(public_path('assets/images/level/'), $imageName);
            $path = 'assets/images/level/' . $imageName;
        }

        $product->code = $code;
        $product->level = $level;
        $product->level_value = $level_value;
        $product->name = $name;
        $product->price = $price;
        $product->price_num = $price_num;
        $product->image = $path;
        // $product->status = 1;
        $product->point = $point;
        $product->save();

        DB::commit();


        $data = [
            'title' => 'บันทึกสำเร็จ!',
            'msg' => 'บันทึก Package สำเร็จ',
            'status' => 'success',
        ];
        return $data;
    }

    public function changeStatus(Request $req)
    {

        $id = $req->id;

        DB::beginTransaction();

        $status = 0;
        $product = Product::find($id);
        $oldStatus = $product->status;

        if($oldStatus == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        $product->status = $status;
        $product->save();

        DB::commit();

        $data = [
            'title' => 'สำเร็จ!',
            'msg' => 'แก้ไขสำสำเร็จ',
            'status' => 'success',
        ];

        return $data;
    }


}
