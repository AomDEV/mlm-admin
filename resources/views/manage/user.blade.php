@extends('layouts.master')

@section('title') User List @endsection

@section('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Management @endslot
        @slot('title') User List @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">รายชื่อสมาชิก</h4>

                    <table id="userListTable" class="table table-bordered dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th></th>
                                <th>วันที่ลงทะเบียน</th>
                                <th>รหัสสมาชิก</th>
                                <th>ชื่อ - นามสกุล</th>
                                <th>ผู้แนะนำ</th>
                                <th>อัพไลน์</th>
                                <th>แนะนำ​แล้ว</th>
                                <th>Cash</th>
                                <th>Coin</th>
                                <th>มือถือ</th>
                                <th>อีเมล์</th>
                                <th>ไลน์ไอดี</th>        
                            </tr>
                        </thead>
                        <tbody>
   
                        </tbody>
                    </table>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->

    

@endsection
@section('script')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/pdfmake/pdfmake.min.js') }}"></script>
<script>
$(document).ready(function() {
    
    // DataTable
    $('#userListTable').DataTable({
        "searching": true,
        "search": {
            "smart": true,
            "caseInsensitive": false
        },
        processing: true,
        serverSide: true,
        "paging": true,
        "pageLength": 25,
        "ajax": {
            "url": "{{ route('getUserList') }}",
            "method": "post",
            "data": {
                        "_token": "{{ csrf_token()}}",
            }
        },
        "columnDefs": [

            {
                "targets": 0,
                data: 'id',
                defaultContent: "",
                "className": "text-center",
                "width": "8%",
                orderable: false,
                searchable: false,
                "render": function(data, type, row, meta) {
                            
                            var urlProfile = "{{ route('viewUser',':id') }}"
                            urlProfile = urlProfile.replace(':id', data);
                            var viewBtn = `<button class="mr-2 btn waves-effect waves-light btn-light" onclick="location.href='${urlProfile}'" ><i class = "fas fa-pencil-alt" style="color:gray;"></i></button>`;
                        return viewBtn;
                    
                }
            },
            {
                "targets": 1,
                data: 'create_date',
                "className": "text-center",
                defaultContent: "",
                orderable: true,
                searchable: false,
            },
            {
                "targets": 2,
                data: 'id',
                "className": "text-center",
                defaultContent: "",
                orderable: false,
                searchable: true,
            },
            {
                "targets": 3,
                data:'fullname',
                orderable: false,
                searchable: true,
            },
            {
                "targets": 4,
                data: 'invite', //รหัสผู้แนะนำ
                defaultContent: "",
                orderable: false,
                searchable: false,
            },
            {
                "targets": 5,
                data: 'upline', //รหัสอัพไลน์
                defaultContent: "",
                orderable: true,
                searchable: false,
            },
            {
                "targets": 6,
                data: 'invite_count', //แนะนำไปกี่คน
                defaultContent: "",
                orderable: true,
                searchable: false,
            },
            {
                "targets": 7,
                data: 'cash_balance', //cashwallet
                defaultContent: "0",
                orderable: true,
                searchable: false,
                "render": function(data, type, row, meta) {
                        var urlCash = "{{ route('cashHistory',':id') }}";
                        urlCash = urlCash.replace(':id', row.id);
                        return `<a href="${urlCash}">${data}</a>`;
                }
            },
            {
                "targets": 8,
                data: 'coin_balance', //coinwallet
                defaultContent: "0",
                orderable: true,
                searchable: false,
                "render": function(data, type, row, meta) {
                        var urlCoin = "{{ route('coinHistory',':id') }}";
                        urlCoin = urlCoin.replace(':id', row.id);
                        return `<a href="${urlCoin}">${data}</a>`;
                }
            },
            {

                "targets": 9,
                defaultContent: "",
                data: 'phone_number',
                orderable: false,
                searchable: true,
            },
            {
                "targets": 10,
                orderable: false,
                searchable: true,
                defaultContent: "",
                name: 'email',
                data: 'email',
            },
            {

                "targets": 11,
                data: 'line',
                defaultContent: "",
                name: 'line',
                orderable: false,
                searchable: false,
            },

        ]

    });


});
</script>
@endsection
