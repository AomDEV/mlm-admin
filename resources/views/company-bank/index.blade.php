@extends('layouts.master')

@section('title') บัญชีธนาคารสำหรับโอนเงินเข้า @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            }
    </style>
    <style>

    </style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title') บัญชีธนาคารสำหรับโอนเงินเข้า @endslot
    @endcomponent
   <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                        <i class="bx bx-check-double" ></i>

                    </div>

                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
         <div class="col-md-3"></div>
    </div>


    <div class="row">

        <div class="col-md-3"></div>

        <div class="col-md-6">
            <div class="row">
                    <div class="col-md-12">
                        <h4> <i class="bx bxs-bank"></i> บัญชีธนาคารสำหรับโอนเงินเข้า </h4>
                    </div>
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('company-bank.store') }}" class="form-horizontal" method="POST" enctype="multipart/form-data" id="package-form">
                                    @csrf


                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">ชื่อธนาคาร</label>
                                        <input type="text" name="bank_name" id="bank_name" value="{{ @$bank->bank_name }}" class="form-control" placeholder="-" >
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank_account_name" class="form-label">ชื่อบัญชี</label>
                                        <input type="text" name="bank_account_name" id="bank_account_name" value="{{ @$bank->bank_account_name }}" class="form-control" placeholder="-" >
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank_account_no" class="form-label">เลขที่บัญชี</label>
                                        <input type="text" name="bank_account_no" id="bank_account_no" class="form-control" value="{{ @$bank->bank_account_no }}" placeholder="-" >
                                    </div>

                                    <div class="mb-3">
                                        <label for="bank_branch" class="form-label">สาขา</label>
                                        <input type="text" name="bank_branch" id="bank_branch" class="form-control" value="{{ @$bank->bank_branch }}" placeholder="-" >
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit"> บันทึก </button>
                                    </div>


                                </form>
                            </div>
                        </div>
                  </div>
            </div>
        </div>

        <div class="col-md-3"></div>
    </div>

@endsection

@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <!-- blog dashboard init -->
    <script src="{{ URL::asset('/assets/js/pages/dashboard-blog.init.js') }}"></script>
     <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs//moment/moment.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ URL::asset('/assets/js/pages/datatables.init.js') }}"></script>
       <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Sweet alert init js-->
    <script src="{{ URL::asset('/assets/js/pages/sweet-alerts.init.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script>
        $(document).ready(function () {

            var simple = '';

        });


        $('#package-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            console.log('OK');
            $.ajax({
                type: "method",
                method: "POST",
                url: "{{ route('company-bank.store') }}",
                processData: false,
                contentType: false,
                data: formData,
                success: function (res) {
                    console.log(res);
                    console.log('successsss');
                    Swal.fire(res.title, res.msg, res.status);
                     $('#simpleModal').modal("hide");
                    simple.ajax.reload();
                }
            });
        });

        function changeStatus(id){
                $.post("{{  route('package.change-status')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                function (res) {
                    simple.ajax.reload();
                },
            );
        }




        </script>
@endsection
