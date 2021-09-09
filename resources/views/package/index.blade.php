@extends('layouts.master')

@section('title')Package @endsection
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
        @slot('title') Package @endslot
    @endcomponent

    <div class="row">

        <!-- end col -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-flex align-items-start">
                          <button class="btn btn-primary" style="float: right;" type="button" id="create_btn"> เพิ่ม Package </button>
                    </div>
                    <br> --}}
                    <div class="row">

                        <table id="simple_table" class="table table-bordered dt-responsive  nowrap w-100">
                            <thead>
                                <tr>
                                    <th scope="col">รูปภาพ</th>
                                    <th scope="col">Level</th>
                                    {{-- <th scope="col">รหัส Package</th> --}}
                                    <th scope="col">ชื่อ Package</th>
                                    <th scope="col">ราคา(ตัวหนังสือ)</th>
                                    <th scope="col">ราคา(ตัวเลข)</th>
                                    <th scope="col">มูลค่า Package(S)</th>
                                    <th scope="col">เปิด-ปิด</th>
                                   <!-- <th scope="col"></th> -->
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
                            <label for="level" class="form-label">Level</label>
                            <input type="text" class="formInput form-control" id="level" value="" name="level"
                                placeholder="กรอก level" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">ชื่อ Package</label>
                            <input type="text" class="formInput form-control" id="name" value="" name="name"
                                placeholder="กรอกชื่อ Package" required>
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">CODE</label>
                            <input type="text" class="formInput form-control" id="code" value="" name="code"
                                placeholder="กรอก code" >
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">ราคา(ตัวหนังสือ) </label>
                            <input type="text" name="price" id="price" step="0.01" min="1" class="formInput form-control" placeholder="กรอกราคา" required>
                        </div>

                        <div class="mb-3">
                            <label for="price_num" class="form-label">ราคา(ตัวเลข) </label>
                            <input type="number" name="price_num" id="price_num" step="1" min="1" class="formInput form-control" placeholder="กรอกราคา" required>
                        </div>

                        <div class="mb-3">
                            <label for="point" class="form-label">คะแนน</label>
                            <input type="number" name="point" id="point" step="1" min="1" class="formInput form-control" placeholder="กรอกคะแนน " required>
                        </div>

                        <div class="mb-3">
                            <label for="point" class="form-label">มูลค่า</label>
                            <input type="number" name="level_value" id="level_value" step="1" min="1" class="formInput form-control" placeholder="กรอกมูลค่า " required>
                        </div>


                        <div class="mb-3">
                            <label for="image" class="form-label">รูป Package </label>

                            <input type="file" class="form-control formInput" accept="image/*" name="imageFile" id="imageFile" placeholder="กรุณาเลือกรูปภาพ"  >
{{--
                            <button style="display:block;" type="button" class="form-control" onclick="document.getElementById('imgFile').click()"> อัพโหลดรูป </button> --}}
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">รูป Package ล่าสุด </label>
                            <img id="output" max-width="300" style="max-height: 300px;" class="img-responsive form-control" />
                        </div>

                        {{--
                        <div class="mb-3">
                            <label for="image" class="form-label">รูป Package </label>

                            <input type="file" class="form-control formInput" accept="image/*" name="" id="imgFile" placeholder="กรุณาเลือกรูปภาพ" style="display:none" onchange="loadFile(event)" >
                            <input type="hidden" id="imgbase64" name="imgbase64" value="" />
                            <button style="display:block;" type="button" class="form-control" onclick="document.getElementById('imgFile').click()"> อัพโหลดรูป </button>
                        </div>

                        <div class="mb-3">
                            <img id="output" max-width="300" style="max-height: 300px;" class="img-responsive form-control" />
                        </div> --}}


                        <div class="mt-3 d-grid">
                            <button class="btn btn-primary waves-effect waves-light" type="submit"> บันทึก </button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!--  Picture modal example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" id="infoModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myLargeModalLabel">รูปภาพ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                        <img id="output1" max-width="300" style="max-height: 500px;" class="img-responsive form-control" />
                  </div>
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
                    "url": "{{ route('package.show') }}",
                    "method": "POST",
                    "data": {
                        "_token": "{{ csrf_token()}}",
                    },
                },
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4,5,6],
                        "className": "text-center",
                    },
                ],
                "columns": [
                    {
                        "data": "id",
                        "render": function (data, type, full) {

                            var text = `<img src="" alt="" class="avatar-md h-auto d-block rounded center">`;
                            if(full.image){
                                text = `<a href="#" onclick="showInfoImg('${full.image}')"><img src="{{ URL::asset('${full.image}') }}" alt="" class="avatar-md h-auto d-block rounded center"></a>`;
                            }

                            return text;
                        }
                    },
                    {
                        "data": "level",
                    },
                    {
                        "data": "name",
                    },
                    {
                        "data": "price",
                    },
                    {
                        "data": "price_num",
                        "render": function (data, type, full) {
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "level_value",
                        "render": function (data, type, full) {
                            return data
                        }
                    },
                    {
                        "data": "status",
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
                    /*
                    {
                        "data": "id",
                        "render": function (data, type, full) {
                            var obj = JSON.stringify(full);
                            var button = `

                            <button type="button" class="btn btn-sm btn-info" onclick='showInfo(${obj})'> แก้ไข </button>
                            `;
                            return button;

                        }
                    },
                    */
                ],
            });
        });


        // <button type="button" class="btn btn-sm btn-danger" onclick='destroy(${data})'> ลบ </button>
        $(".create_btn").click(function () {
            console.log('sjpe');
            document.getElementById("imgFile").value = "";
            document.getElementById("imgbase64").value = "";
            $('#modal_title').text('เพิ่ม Package ใหม่');
            $('.formInput').val('');
            $('#output').attr('src','');
            $('#simpleModal').modal("show");
        });

        $('#package-form').submit(function(e){
            e.preventDefault();
            let formData = new FormData(this);
            console.log('OK');
            $.ajax({
                type: "method",
                method: "POST",
                url: "{{ route('package.store') }}",
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

        function showInfoImg(img){
            $('#infoModal').modal('show');
             $('#output1').attr('src', `{{ URL::asset('${img}') }}`);
        }


        function showInfo(obj){

            $('#modal_title').text('แก้ไข Package');
            $('#simpleModal').modal("show");
            console.log(obj);
            $('#id').val(obj.id);
            $('#level').val(obj.level);
            $('#name').val(obj.name);
            $('#price').val(obj.price);
            $('#price_num').val(obj.price_num);
            $('#point').val(obj.point);
            $('#code').val(obj.code);
            $('#level_value').val(obj.level_value);
            
            $('#output').attr('src', `{{ URL::asset('${obj.image}') }}`);
        }

        var loadFile = function(event) {
            // var image = document.getElementById('output');
            // image.src = URL.createObjectURL(event.target.files[0]);
            resizeImages(event.target.files[0],function(url){
                $('#imgbase64').val(url);
            });

            var reader = new FileReader();
            reader.onload = function(e) {
                $('#output').attr('src', e.target.result);
            }
            reader.readAsDataURL(event.target.files[0]);
        };

        function resizeImages(file, com) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    com(resizeInCanvas(img));
                };
                img.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }

        function resizeInCanvas(img) {
            var perferedWidth = 2048;
            var ratio = perferedWidth / img.width;
            var canvas = $("<canvas>")[0];
            canvas.width = img.width * ratio;
            canvas.height = img.height * ratio;
            var ctx = canvas.getContext("2d");
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
            var imgfile = canvas.toDataURL('image/jpeg', 0.5);
            return imgfile;
        }
        </script>
@endsection
