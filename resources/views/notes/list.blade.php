
@extends('layouts.base')




@section('style')

    <!-- side_container.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">
    <style>
        .note_master_only{ display: none;}
    </style>
@endsection




@section('script')


@endsection







@section('title','ユーザーさんのマイページ')





@section('main.breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item active" aria-current="page">ユーザーさんのマイページ</li>
        </ol>
    </nav>
@endsection




@section('main.side_container')

    @include('includes.side_container.list')

@endsection




@section('main.center_container')

    <h5 class="d-flex justify-content-between align-items-center mb-3" style="padding-right:.5em">
        <div class="fs-3"><i class="bi bi-book"></i> 新着投稿</div>
        <button class="note_master_only btn btn-lg btn-primary" style="font-size:.8em;"><i class="bi bi-file-earmark-plus"></i> 新規作成</button>
    </h5>



    <ul class="list-group mb-3">


        <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="note_master_only badge rounded-pill bg-success">公開中</span>
                </small>


                <div class="">
                    <a href=""  class="">
                        <h5>List group item heading</h5>
                    </a>
                </div>


                <small class="text-muted">
                    <small><i class="bi bi-tag-fill"></i>And some muted p print.</small>
                </small>

            </div>


            <div class="note_master_only col-md-3 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
            </div>

        </li>




        <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="note_master_only badge rounded-pill bg-success">公開中</span>
                </small>


                <div class="">
                    <a href=""  class="">
                        <h5>List group item heading</h5>
                    </a>
                </div>


                <small class="text-muted">
                    <small><i class="bi bi-tag-fill"></i>And some muted p print.</small>
                </small>

            </div>


            <div class="note_master_only col-md-3 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
            </div>

        </li>



        <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="note_master_only badge rounded-pill bg-danger">非公開</span>
                </small>


                <div class="">
                    <a href=""  class="">
                        <h5>List group item heading</h5>
                    </a>
                </div>


                <small class="text-muted">
                    <small><i class="bi bi-tag-fill"></i>And some muted p print.</small>
                </small>

            </div>


            <div class="note_master_only col-md-3 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
            </div>

        </li>


    </ul>




    <!-- pagenation -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">前</a>
        </li>
        <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item">
            <a class="page-link" href="#">次</a>
        </li>
        </ul>
    </nav>

@endsection
