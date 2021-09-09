@extends('layouts.master')

@section('title') Admin info {{$userData->name}} @endsection
@section('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Manage @endslot
        @slot('title') Edit Admin @endslot
    @endcomponent

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form id="from_update_admin" method="post">
                @method('POST')
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12 col-sm-12">
                                <div class="mb-3">
                                    <label class="form-label">อีเมล์</label>
                                    <input class="form-control" type="email" id="email" required value="{{$userData->email}}" disabled>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

        </div>
        <!-- end card one -->


        <!-- card two -->
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-body">

                    <div class="row">
                        <div class="col-lg-12 mt-3">
                            <div class="mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">ชื่อ - สกุล</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{$userData->name}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">รหัสไปรษณีย์</label>
                                <input class="form-control" type="text" name="zip_code" id="zip_code" value="{{$userData->zip_code}}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">จังหวัด</label>
                                <input class="form-control" type="text" name="province" id="province" value="{{$userData->province}}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">เขต/อำเภอ</label>
                                <input class="form-control" type="text" name="district" id="district" value="{{$userData->district}}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">แขวง/ตำบล</label>
                                <input class="form-control" type="text" name="sub_district" id="sub_district" value="{{$userData->sub_district}}">
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">รายละเอียดที่อยู่เพิ่มเติม</label>
                                <textarea class="form-control" rows="3" name="address" id="address">{{$userData->address}}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">มือถือ</label>
                                <input class="form-control" type="tel" name="phone_number" id="phone_number" value="{{$userData->phone_number}}">
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Line ID</label>
                                    <input class="form-control" type="text" name="line" id="line" value="{{$userData->line}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Facebook URL</label>
                                    <input class="form-control" type="text" id="fb" name="fb" value="{{$userData->fb}}">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6">
                            <div class="mb-3">
                                <div class="col-lg-12">
                                    <label class="form-label">Instagram URL</label>
                                    <input class="form-control" type="text" id="ig" name="ig" value="{{$userData->ig}}">
                                </div>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

        </div>


        <div class="col-12">
            <center>
                <button id="saveBtn" type="submit" class="btn btn-success btn-lg waves-effect waves-light">บันทึก </button>
            </center>

        </div>
        </form>


    </div>

@endsection

@section('script-bottom')
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/select2/select2.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/thailand/JQL.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/thailand/typeahead.bundle.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/thailand/jquery.Thailand.min.js') }}"></script>
<script>
    $.Thailand({
        $district: $('#sub_district'), // input ของตำบล
        $amphoe: $('#district'), // input ของอำเภอ
        $province: $('#province'), // input ของจังหวัด
        $zipcode: $('#zip_code'), // input ของรหัสไปรษณีย์
    });

    $('#from_update_admin').on('submit',function(event){
        event.preventDefault();
        
        $('#saveBtn').append('<i id="loadingBtn" class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i>');
        $("#saveBtn").attr("disabled", true);
        var csrf = '{{csrf_token()}}';
        var userId = "{{$userData->id}}"
        var email = $('#email').val();
        var password = $('#password_new').val();
        var password_confirmation = $('#password_confirmation').val();
        var name = $('#name').val();
        var sendAddress = $('#address').val();
        var sendProvince = $('#province').val();
        var sendDistrict = $('#district').val();
        var sendSubDistrict = $('#sub_district').val();
        var sendZipCode = $('#zip_code').val();
        var sendPhoneNumber = $('#phone_number').val();
        var sendEmail = $('#email').val();
        var line = $('#line').val();
        var fb = $('#fb').val();
        var ig = $('#ig').val();
        console.log(email);
        $('#current_passwordErr').text('');
        $('#passwordErr').text('');
        $('#password_confirmErr').text('');
        $.ajax({
            url: "{{ route('adminUpdate') }}",
            type:"post",
            data:{
                "_token": csrf,
                "user_id": userId,
                //"email": email,
                "name": name,
                "address": sendAddress,
                "province": sendProvince,
                "district": sendDistrict,
                "sub_district": sendSubDistrict,
                "zip_code": sendZipCode,
                "phone_number": sendPhoneNumber,
                "line": line,
                "fb": fb,
                "ig": ig,
            },
            
            success:function(response){
                
                $('#passwordErr').text('');
                $('#password_confirmationErr').text('');
                $("#loadingBtn").remove();
                $("#saveBtn").attr("disabled", false);

                if(response.isSuccess == false){
                    console.log(JSON.stringify(response))

                }else if(response.isSuccess == true){
                    console.log(JSON.stringify(response))
                        Swal.fire(
                            'สำเร็จ!',
                             response.Message,
                            'success'
                        )          
                }
                
            },
            error: function(response) {
                console.log(response)
            }
        });
        
       
    });
</script>
@endsection
