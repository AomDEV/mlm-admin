
@extends('layouts.master')

@section('title') รายการขอถอนเงิน   @endsection
@section('css')
    <!-- DataTables -->
    <link href="{{ URL::asset('/assets/libs/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/fontawesome.min.css"  />
    <link rel="stylesheet" href="{{ URL::asset('/assets/libs/owl.carousel/owl.carousel.min.css') }}">
    <link href="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css">
    <style>
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            }
    </style>
@endsection

@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title') รายการขอถอนเงิน   @endslot
    @endcomponent

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <h5>History</h5>
                    <div class="row">

                        <table id="simple_table" style="font-size:90%;" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col">วันที่ทำรายการ</th>
                                    <th scope="col">เลขที่ทำรายการ</th>
                                    <th scope="col">รายละเอียด</th>
                                    <th scope="col">รหัสสมาชิก</th>
                                    <th scope="col">ธนาคาร</th>
                                    {{-- <th scope="col">เลขบัญชี</th> --}}
                                    <th scope="col">จำนวนเงิน</th>
                                    <th scope="col">สถานะ</th>
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
    </div>

         <!--  Note modal example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" id="noteModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"> หมายเหตุ </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row">
                      {{-- <p class="text-center" id="note"> </p> --}}
                      <h5 class="text-center text-danger" id="note"></h5>
                  </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

             <!--  Note modal example -->
    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
        aria-hidden="true" id="infoModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"> ข้อมูลสำหรับโอนเงิน </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- <div class="container"> --}}
                        <div class="row" style="padding: 0px 40px 0px 40px;">
                                <div class="col-md-12">
                                    <span style="font-size:1.2em;"> จำนวนเงิน : </span>  <span style="font-size:1.2em; color: #556EE6;" id="amount">  </span> <br><hr>
                                    <span> ชื่อผู้ใช้ : </span>   <span class="text-right" id="username">  </span><br><hr>
                                    <span> ธนาคาร : </span>  <span class="text-right" id="bank">  </span><br><hr>
                                    <span> เลขที่บัญชี : </span>  <span class="text-right" id="account_no">  </span><br><hr>
                                    <span> ชื่อบัญชี : </span>   <span class="text-right" id="account_name">  </span><br>
                                </div>

                        </div>
                    {{-- </div> --}}
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id" value="">
                    <button type="button" class="btn btn-primary" onclick="withdrawV2(1)"> โอนเงินแล้ว </button>
                    <button type="button" class="btn btn-danger" onclick="withdrawV2(2)"> ยกเลิกรายการ </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> ปิด </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



@endsection

@section('script')

    <script src="https://unpkg.com/boxicons@2.0.9/dist/boxicons.js"></script>

    <script src="{{ URL::asset('/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
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
    <script>

        $(document).ready(function () {
            var simple = '';
        });

        function withdrawCash(){
            var amount = $('#amount').val();
            var bankAccountId = $('#bankAccountId').val();
            console.log(bankAccountId);
            if(amount == null || amount == undefined || amount == ''){
                Swal.fire('แจ้งเตือน!', 'กรุณากรอกจำนวนเงิน', 'warning');
            }else if(bankAccountId == ''){
                Swal.fire('แจ้งเตือน!', 'กรุณากรอกข้อมูลธนาคาร', 'warning');
            }else{
                amount = amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่ ?',
                    text: `คุณต้องการถอนเงินจำนวน ${amount} บาทนี้หรือไม่ `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                    }).then((result) => {
                        if (result.value) {
                            $.post("{{  route('admin.withdraw.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                amount: amount,
                                bankAccountId: bankAccountId,
                                },
                                function (res) {
                                    Swal.fire(res.title, res.msg, res.status);
                                    simple.ajax.reload();
                                    $('#amount').val('');
                                     callBalance();
                                },
                            );
                        }

                    });



            }
        }

        $('#simple_table').ready(function () {
            simple = $('#simple_table').DataTable({
                "processing": true,
                "serverSide": true,
                "info": false,
                "searching": true,
                "responsive": true,
                "bFilter": true,
                "destroy": true,
                "order": [
                    [0, "desc"]
                ],
                "ajax": {
                    "url": "{{ route('admin.withdraw.show') }}",
                    "method": "POST",
                    "data": {
                        "_token": "{{ csrf_token()}}",
                    },
                },
                'columnDefs': [
                    {
                        "targets": [0,1,2,3,4,5,6,7],
                        "className": "text-center",
                    },
                ],
                "columns": [

                    {
                        "data": "transaction_timestamp",
                         "render": function (data, type, full) {
                            return moment(data).format('DD-MM-YYYY HH:mm');
                        }
                    },
                    {
                        "data": "code",

                    },

                    {
                        "data": "detail",

                    },
                      {
                        "data": "user_id",
                    },

                    {
                        "data": "id",
                        "render": function (data, type, full) {
                                var text = ''
                            if(full.bank){
                                text = full.bank.name;
                            }

                            return text;
                        }
                    },
                    // {
                    //     "data": "bank_account_no",
                    // },

                    {
                        "data": "amount",
                        "render": function (data, type, full) {
                            return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    },
                    {
                        "data": "status",
                        "render": function (data, type, full) {
                            var text = ''
                            switch (data) {
                                case 0:
                                    text = '<span class="text-warning"> รอดำเนินการ </span>'
                                    break;
                                case 1:
                                     text = '<span class="text-success"> สำเร็จ </span>'
                                    break;
                                case 2:
                                      text = `<a href="#" onclick="showNote('${full.note ? full.note : '' }')" class="text-danger"> <u>ยกเลิก<u> </a>`;
                                    break;
                                default:
                                    break;
                            }

                            var balance = 0;
                            var amount = 0;
                            if(full.user.cash_wallet && data == 0){
                                amount = parseFloat(full.amount);
                                balance = parseFloat(full.user.cash_wallet.balance);
                                console.log(balance);
                                console.log(amount);
                                if(amount > balance){
                                    text = '<span class="text-danger"> ยอดเงินใน wallet ไม่เพียงพอ </span>'
                                }
                            }

                            return text;
                        }

                    },
                       {
                        "data": "status",
                        "render": function (data, type, full) {
                            var obj = JSON.stringify(full);
                            // var text = `<button type="button" class="btn btn-sm btn-primary" onclick='withdraw(${obj}, 1)'> โอนเงินแล้ว </button>
                            // <button type="button" class="btn btn-sm btn-danger" onclick='withdraw(${obj}, 2)'> ยกเลิก </button>
                            // `;
                            var text = '';

                            if(data == 0){
                                text = `<button type="button" class="btn btn-sm btn-primary" onclick='showInfo(${obj})'> โอนเงิน </button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick='withdraw(${obj}, 2)'> ยกเลิก </button>
                                    `;
                            }else{
                                text = `<button type="button" class="btn btn-sm btn-secondary" disabled> โอนเงิน </button>
                                        <button type="button" class="btn btn-sm btn-secondary" disabled> ยกเลิก </button>
                                    `;
                            }

                            var balance = 0;
                            var amount = 0;
                            if(full.user.cash_wallet && data == 0){
                                console.log('ok');
                                amount = parseFloat(full.amount);
                                balance = parseFloat(full.user.cash_wallet.balance);
                                // balance = full.user.cash_wallet.balance;
                                if(amount > balance){
                                     text = `<button type="button" class="btn btn-sm btn-secondary" disabled> โอนเงิน </button>
                                                  <button type="button" class="btn btn-sm btn-danger" onclick='withdraw(${obj}, 2)'> ยกเลิก </button>
                                    `;
                                }
                            }

                            return text;
                        }
                    },
                ],
            });
        });

        function withdraw(obj, status){
            var amount = (obj.amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            var username = obj.user.username;
            var id = obj.id;
            if(status == 1){
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะโอนเงิน ${amount} ให้กับสมาชิกเลขที่ ${id} ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {
                        $.post("{{  route('admin.withdraw.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                status: status,
                            },
                            function (res) {
                                $('#infoModal').modal('hide');
                                Swal.fire(res.title, res.msg, res.status);

                                simple.ajax.reload();
                            },
                        );
                    }
                });
            }else{
                var note = '';
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะยกเลิกโอนเงิน ${amount} ให้กับสมาชิกเลขที่ ${id} ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#7A7978',
                    cancelButtonColor: '#556ee6',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {


                            Swal.fire({
                                title: 'กรุณากรอกเหตุผล',
                                input: 'textarea',
                                inputPlaceholder: 'กรอกเหตุผลที่ยกเลิก...',
                                inputAttributes: {
                                    'aria-label': 'กรอกเหตุผลที่ยกเลิก'
                                },
                                showCancelButton: true,
                                customClass: {
                                    container: 'my-swal'
                                }
                            }).then((result) => {
                                if (result.value) {
                                    note = result.value;
                                    if(note != null){
                                        $.post("{{  route('admin.withdraw.store')  }}", data = {
                                            _token: '{{ csrf_token() }}',
                                            id: id,
                                            status: status,
                                            note: note,
                                        },
                                            function (res) {
                                                $('#infoModal').modal('hide');
                                                Swal.fire(res.title, res.msg, res.status);
                                                simple.ajax.reload();
                                            },
                                        );
                                    }else{
                                        Swal.fire('กรุณากรอกเหตุผล!', 'เหตุผลจำเป็นต้องกรอก', 'warning');
                                    }
                                }
                            });

                    }
                });
            }
        }

        function showNote(note){
            // console.log(note)
            $('#noteModal').modal('show');
            $('#note').text(note);
        }

        function showInfo(obj){
            console.log(obj)
            $('#infoModal').modal('show');
            $('#id').val(obj.id);
             $('#amount').text(obj.amount);
            $('#username').text(obj.user.username)
            $('#bank').text(obj.bank.name)
            $('#account_no').text(obj.bank_account_no)
            $('#account_name').text(obj.bank_account_name)
        }

        function withdrawV2(status){
            var id = $('#id').val();
            if(status == 1){
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะโอนเงินให้รายการนี้หรือไม่`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#556ee6',
                    cancelButtonColor: '#7A7978',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {
                        $.post("{{  route('admin.withdraw.store')  }}", data = {
                                _token: '{{ csrf_token() }}',
                                id: id,
                                status: status,
                            },
                            function (res) {
                                $('#infoModal').modal('hide');
                                Swal.fire(res.title, res.msg, res.status);
                                simple.ajax.reload();
                            },
                        );
                    }
                });
            }else{
                var note = '';
                Swal.fire({
                    title: 'คุณมั่นใจหรือไม่?',
                    text: `ที่จะยกเลิกราการโอนเงืนนี้ ?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#7A7978',
                    cancelButtonColor: '#556ee6',
                    cancelButtonText: 'ยกเลิก',
                    confirmButtonText: 'ตกลง',
                }).then((result) => {
                    if (result.value) {


                            Swal.fire({
                                title: 'กรุณากรอกเหตุผล',
                                input: 'textarea',
                                inputPlaceholder: 'กรอกเหตุผลที่ยกเลิก...',
                                inputAttributes: {
                                    'aria-label': 'กรอกเหตุผลที่ยกเลิก'
                                },
                                showCancelButton: true,
                                customClass: {
                                    container: 'my-swal'
                                }
                            }).then((result) => {
                                if (result.value) {
                                    note = result.value;
                                    if(note != null){
                                        $.post("{{  route('admin.withdraw.store')  }}", data = {
                                            _token: '{{ csrf_token() }}',
                                            id: id,
                                            status: status,
                                            note: note,
                                        },
                                            function (res) {
                                                $('#infoModal').modal('hide');
                                                Swal.fire(res.title, res.msg, res.status);
                                                simple.ajax.reload();
                                            },
                                        );
                                    }else{
                                        Swal.fire('กรุณากรอกเหตุผล!', 'เหตุผลจำเป็นต้องกรอก', 'warning');
                                    }
                                }
                            });

                    }
                });
            }
        }



    </script>
@endsection


