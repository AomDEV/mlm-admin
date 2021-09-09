@extends('layouts.master')

@section('title') แก้ไขข่าวสาร @endsection
@section('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
<style>
    .ck-editor__editable {
        min-height: 300px;
        max-height: 800px;
        min-width: 600px;
    }

    .ck-editor__top {
        min-width: 600px;
    }
</style>
@endsection
@section('content')

    @component('components.breadcrumb')
        @slot('li_1') Manage @endslot
        @slot('title') News @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">แก้ไขข่าวสาร</h4>

                    <form method="post" action="{{route('updateNews')}}">
                        @csrf
                        <textarea class="mb-3" id="editor" name="news_body" rows="6">{{ $newsData->body }}</textarea>
                        <center>
                            <button id="saveBtn" type="submit" class="btn btn-success btn-lg waves-effect waves-light mt-3">บันทึก </button>
                        </center>
                    </form>

                </div>
            </div>
        </div> <!-- end col -->
    </div> <!-- end row -->


@endsection
@section('script-bottom')
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

<script src="#"></script>
<script>
    /*
    ClassicEditor
        .create( document.querySelector('#editor'))
        .catch( error => {
            console.error( error );
    });
    */


    CKEDITOR.replace( 'editor', {
        filebrowserUploadUrl: "{{route('uploadImageNews', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });

        
</script>
@endsection
