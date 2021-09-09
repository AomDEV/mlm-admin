@extends('layouts.master')

@section('title') Admin List @endsection

@section('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<!-- DataTables -->
<link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Management @endslot
        @slot('title') Admin List @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title mb-3">รายชื่อผู้ดูแล</h4>

                    <table id="adminTable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead>
                            <tr>
                                <th>อีเมล</th>
                                <th>ชื่อ</th>
                                <th>เบอร์โทร์ศัพท์</th>
                                <th>ไลน์ไอดี</th>
                                <th>เฟสบุ๊ค</th>
                                <th></th>
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
@section('script-bottom')
    <!-- Required datatable js -->
    <script src="{{ URL::asset('/assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
    const deleteAdmin = (e) => {
        let id = e.getAttribute('data-id');

        const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger '
                },
                buttonsStyling: true
            });

            swalWithBootstrapButtons.fire({
                title: "คุณต้องการลบหรือไม่ ?",
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'ใช่ ต้องการลบ',
                cancelButtonText: 'ไม่ ต้องการ',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
            
                    if (result.isConfirmed){

                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('deleteAdmin') }}",
                            data:{
                                "_token": "{{ csrf_token() }}",
                                "id":id
                            },
                            success:function(response) {
                                if (response.isSuccess){
                                    $('#adminTable').DataTable().ajax.reload();
                                    Swal.fire(
                                        'Success !',
                                         response.Message,
                                        'success'
                                    );
                                }else{
                                    $('#adminTable').DataTable().ajax.reload();
                                    Swal.fire(
                                        'Notice !',
                                         response.Message,
                                        'warning'
                                    );
                                }

                            }
                        });

                    }

                } else if ( result.dismiss === Swal.DismissReason.cancel) {
                    
                }
            });
    }
</script>

<script type="application/javascript">
$(document).ready(function() {
    // DataTable
    $('#adminTable').DataTable({
        "searching": true,
        "search": {
            "smart": true,
            "caseInsensitive": false
        },
        processing: true,
        serverSide: true,
        "paging": true,
        "pageLength": 10,
        "ajax": {
            "url": "{{ route('getAdminList') }}",
            "method": "post",
            "data": {
                        "_token": "{{ csrf_token()}}",
            }
        },
        "columnDefs": [
            
            {
                "targets": 0,
                data: 'email',
                defaultContent: "",
                orderable: false,
                searchable: true,
            },
            {
                "targets": 1,
                data: 'name',
                orderable: false,
                searchable: true,
            },
            {
                "targets": 2,
                orderable: false,
                searchable: true,
                defaultContent: "",
                data: 'phone_number',
            },
            {

                "targets": 3,
                defaultContent: "",
                data: 'line',
                orderable: false,
                searchable: false,
            },
            {

                "targets": 4,
                data: 'fb',
                defaultContent: "",
                name: 'fb',
                orderable: false,
                searchable: false,
            },
            {
                "targets": 5,
                data: 'id',
                defaultContent: "",
                "className": "text-center",
                "width": "10%",
                orderable: false,
                searchable: false,
                "render": function(data, type, row, meta) {
                            
                            var urlProfile = "{{ route('viewAdmin',':id') }}"
                            urlProfile = urlProfile.replace(':id', data);

                            var viewBtn = `<button class="mr-2 btn waves-effect waves-light btn-light" onclick="location.href='${urlProfile}'" ><i class = "fas fa-pencil-alt" style="color:gray;"></i></button>`;
                            var deleteAdminBtn = `<button class="btn waves-effect waves-light btn-light" data-id="${data}" onclick="deleteAdmin(this);" ><i class = "far fa-trash-alt" style="color:gray;"></i></button>`;
                        return viewBtn +" "+ deleteAdminBtn;
                    
                }
            },
        ]

    });


});
</script>
@endsection
