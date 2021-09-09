<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Datatables;

class ManageAdminController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $adminList = User::All();
        return view("manage.adminlist",['adminList'=> $adminList]);
        
    }

    public function getAdminList()
    {
        if(request()->ajax()) {

            return datatables()->of(
                User::orderBy('updated_at', 'desc')->get()
            )->toJson();
        }
    }

    public function addAdminIndex()
    {

        return view("manage.add");

    }

    public function addAdmin(Request $req)
    {
        $validator = Validator::make($req->all(), [
            
            'email' => ['required', 'string', 'email', 'max:255', 'unique:admin_users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if($validator->fails())
        {
            return response()->json([
                'isSuccess' => false,
                'status'=>400,
                'errors'=>$validator->messages()
            ]);
        }

        $user = new User();
        $user->email = $req->get('email');
        $user->password = Hash::make($req->get('password'));
        $user->name = $req->get('name');
        $user->address = $req->address;
        $user->province = $req->province;
        $user->district = $req->district;
        $user->sub_district = $req->sub_district;
        $user->zip_code = $req->zip_code;
        $user->phone_number = $req->phone_number;
        $user->line = $req->line;
        $user->fb = $req->fb;
        $user->ig = $req->ig;
        $status = $user->save();
        if($status){
            return response()->json([
                'isSuccess' => true,
                'status'=>200,
                'Message'=> 'เพิ่มผู้ดูแลเรียบร้อย'
            ]);
        }

    }

    public function viewAdmin($id)
    {
        $userData = User::find($id);
        if($userData == null){
            return redirect()->route('adminList');
        }

        return view("manage.viewadmin",['userData'=> $userData]);
    }

    public function adminUpdate(Request $request){


        $userData = User::find($request->get('user_id'));
        
        if($userData){

            $form_user = array(

                'name' => $request->name,
                'line' => $request->line,
                'fb' => $request->fb,
                'ig' => $request->ig,
                'address' => $request->address,
                'province' => $request->province,
                'sub_district' => $request->sub_district,
                'district' => $request->district,
                'zip_code' => $request->zip_code,
                'phone_number' => $request->phone_number

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

        }else {
            return response()->json([
                'isSuccess' => false,
                'status'=>404,
                'Message'=>'Not Found.'
            ]);
        }
    }

    public function deleteAdmin(Request $req)
    {
        $adminData = User::find($req->get('id'));

        if($adminData->delete()){
            return response()->json([
                'isSuccess' => true,
                'status'=>200,
                'Message'=> 'การลบข้อมูลสำเร็จ'
            ]);
        }else{
            return response()->json([
                'isSuccess' => true,
                'status'=> 500,
                'Message'=> 'ไม่สามารถทำรายการได้ โปรลองใหม่ในภายหลัง'
            ]);
        }
        
    }

}
