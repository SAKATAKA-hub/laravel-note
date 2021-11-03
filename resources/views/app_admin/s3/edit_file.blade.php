@extends('layouts.base')




@section('style')
    <style>
        .show_image{
            width: 200px;
            height: 200px;
            border: solid 1px #eee;
            border-radius: 10px;
        }
    </style>
@endsection




@section('script')
@endsection






@section('title','AWS S3アップロードファイルの操作')





@section('main.breadcrumb')
    AWS S3アップロードファイルの操作
@endsection




@section('main.side_container')
@endsection








@section('main.top_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        AWS S3アップロードファイルの操作
    </h2>


    @switch($mode)
        @case('show_file')
            <div class="mb-5">
                <h5>ファイルの表示</h5>
                <img src="{{$url}}" alt="" class="show_image">
                <div>File URL : {{$url}}</div>
                <div>File path : {{$path}}</div>
            </div>
            @break

        @case('upload_file'))
            <div class="mb-5">
                <h5>ファイルをアップロードしました。</h5>
                <img src="{{$url}}" alt="" class="show_image">
                <div>File URL : {{$url}}</div>
                <div>File path : {{$path}}</div>
            </div>
            @break

        @case('delete_file'))
            <div class="mb-5">
                <h5>{{$text}}</h5>
            </div>
            @break

        @default

    @endswitch


    <div class="mb-5">
        <h5>ファイルのアップロード</h5>
        <form action="{{route('app_admin.s3.upload_file')}}" method="post" enctype="multipart/form-data">
            @csrf
            ディレクトリ<input type="text" name="dir" class="me-3">
            <input type="file" name="file" required>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>


    <div class="mb-5">
        <h5>アップロードファイルの表示(ファイルパスを入力)</h5>
        <form action="{{route('app_admin.s3.show_file')}}" method="post" enctype="multipart/form-data" class="">
            @csrf
            <input type="text" name="path" style="width:30em;" required>
            <button type="submit" class="btn btn-primary">表示</button>
        </form>
    </div>


    <div class="mb-5">
        <h5>アップロードファイルの削除(ファイルパスを入力)</h5>
        <form action="{{route('app_admin.s3.delete_file')}}" method="post" enctype="multipart/form-data" class="">
            @csrf
            <input type="text" name="path" style="width:30em;" required>
            <button type="submit" class="btn btn-primary">削除</button>
        </form>
    </div>



    <div class="pt-5 pb-5 ">
        <a href="{{route('app_admin.top')}}" class="btn btn-link">管理者ページに戻る</a>
    </div>




@endsection

