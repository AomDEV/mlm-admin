@extends('layouts.master')

@section('title') ค้นหาประวัติ Cash wallet @endsection


@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') รายละเอียดสมาชิก @endslot
        @slot('title') ค้นหาประวัติ Cash wallet @endslot
    @endcomponent
    <div class="row">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card">
                    <div class="card-body">
                        
                            <div class="form-group row mb-3">
                                <center>
                                    
                                    <div class="col-md-6">
                                        <h5 for="user_id"><b>รหัสสมาชิก</b></h5>
                                    <input id="user_id" type="text" class="form-control" name="user_id" autocomplete="user_id" placeholder="">
                                    <div class="text-danger" id="user_idErr" data-ajax-feedback="user_id"></div>
                                    </div>
                                </center>
                            </div>

                            <div class="form-group row mt-5 mb-0">
                                <center>
                                    <div class="col-md-6">
                                    <button id="submitBtn" type="submit" class="btn btn-primary">
                                        <i class="bx bx-search-alt"></i> ค้นหา
                                    </button>
                                </div>
                                </center>
                            </div>
                       
    
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="{{ URL::asset('/assets/libs/parsleyjs/parsleyjs.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        
    $('#submitBtn').on('click',function(event){
        event.preventDefault();
        $('#user_idErr').text('');
        var userId = $('#user_id').val();
        
        if(userId == ""){
            $('#user_idErr').text('กรุณากรอกรหัสสมาชิก');
        }else{
            var urlProfile = "{{ route('cashHistory',':id') }}"
            urlProfile = urlProfile.replace(':id', userId);
            window.location = urlProfile;
        }

    });
    </script>
@endsection