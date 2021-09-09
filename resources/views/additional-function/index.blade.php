@extends('layouts.master')

@section('title') จัดการ Function เพิ่มเติม @endsection
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
        @slot('title') จัดการ Function เพิ่มเติม @endslot
    @endcomponent

    <div class="row">

        <!-- end col -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                          <button class="btn btn-primary" style="float: right;" type="button" id="create_btn"> เพิ่ม Function </button>
                    </div>
                    <br>
                    <div class="row">

                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="col">CODE</th>
                                    <th scope="col">ชื่อ Function</th>
                                    <th scope="col">แก้ไขล่าสุดเมื่อ</th>
                                    <th scope="col">เปิด-ปิด</th>
                                    <th scope="col"></th>
                                </tr>

                            </thead>

                            <tbody>


                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
    <!--  Update Profile example -->
    <div class="modal fade update-profile" id="simpleModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel"><span id="modal_title"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>

                </div>
                <div class="modal-body">

                    <form class="form-horizontal" method="POST" enctype="multipart/form-data" id="package-form">
                        @csrf
                        <input type="hidden" class="formInput" name="id" value="" id="id">


                        <div class="mb-3">
                            <label for="code" class="form-label">CODE</label>
                            <input type="text" class="formInput form-control" id="code" value="" name="code"
                                placeholder="กรอก code" required>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label"> ชื่อ Function </label>
                            <input type="text" name="name" id="name" class="formInput form-control" placeholder="กรอกชื่อ Function" required>
                        </div>


                        <div class="mt-3 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"> บันทึก </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
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


        $('#simple_table').ready(function () {
            simple = $('#simple_table').DataTable({
                "processing": false,
                "serverSide": false,
                "info": false,
                "searching": true,
                "responsive": true,
                "bFilter": false,
                "destroy": true,
                "order": [
                    [0, "desc"]
                ],
                "ajax": {
                    "url": "{{ route('additional-function.show') }}",
                    "method": "POST",
                    "data": {
                        "_token": "{{ csrf_token()}}",
                    },
                },
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4],
                        "className": "text-center",
                    },
                ],
                "columns": [
                    {
                        "data": "code",
                    },
                    {
                        "data": "name",
                    },
                    {
                        "data": "active",
                        "render": function (data, type, full) {
                            var text = ``;
                            if(data == 1){
                                // text = `<input type="checkbox" onclick="changeStatus(${full.id})" checked data-toggle="toggle" data-size="sm">`;
                                text = `<input type="checkbox" onclick="changeStatus(${full.id})" id="switch${full.id}" switch="primary" checked />
                                    <label for="switch${full.id}" data-on-label="เปิด" data-off-label="ปิด"></label>`;
                            }else{
                                   text = `<input type="checkbox" onclick="changeStatus(${full.id})" id="switch${full.id}" switch="primary"  />
                                    <label for="switch${full.id}" data-on-label="เปิด" data-off-label="ปิด"></label>`;
                            }
                            return text;
                        }
                    },
                               {
                        "data": "updated_at",
                        "render": function (data, type, full) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {
                        "data": "id",
                        "render": function (data, type, full) {
                            var obj = JSON.stringify(full);
                            var button = `

                            <button type="button" class="btn btn-sm btn-info" onclick='showInfo(${obj})'> แก้ไข </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick='destroy(${data})'> ลบ </button>
                            `;
                            return button;

                        }
                    },
                ],
            });
        });


        // <button type="button" class="btn btn-sm btn-danger" onclick='destroy(${data})'> ลบ </button>
        $("#create_btn").click(function () {

            $('#modal_title').text('เพิ่ม Function ใหม่');
            $('.formInput').val('');
            $('#simpleModal').modal("show");
        });

        $('#package-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            console.log('OK');
            $.ajax({
                type: "method",
                method: "POST",
                url: "{{ route('additional-function.store') }}",
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
                $.post("{{  route('additional-function.change-status')  }}", data = {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
                function (res) {
                    simple.ajax.reload();
                },
            );
        }


        function destroy(id){

                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะลบ Function นี้ ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {
                        $.post("{{  route('additional-function.delete')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                            },
                            function (res) {
                                Swal.fire(res.title, res.msg, res.status);
                                simple.ajax.reload();
                            },
                        );
                    }
                });
        }


        function showInfo(obj){

            $('#modal_title').text('แก้ไข Package');
            $('#simpleModal').modal("show");
            $('#id').val(obj.id);
            $('#name').val(obj.name);
            $('#code').val(obj.code);

        }

        </script>
@endsection
