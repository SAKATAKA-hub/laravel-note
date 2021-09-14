
@extends('layouts.base')




@section('title','マイページ')




@section('style')

    <!-- side_container.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">

@endsection




@section('script')


@endsection








@section('main.breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item active" aria-current="page">マイページ</li>
        </ol>
    </nav>
@endsection




@section('main.side_container')

    @include('includes.side_container')

@endsection




@section('main.center_container')
    <h5 class="d-flex w-100 justify-content-between align-items-center">
        <div class="fs-3"><i class="bi bi-book"></i> 新着投稿</div>
        <button class="btn btn-lg btn-primary"><i class="bi bi-file-earmark-plus"></i> 新規作成</button>
    </h5>



    <ul class="list-group mb-3">


        <li class="list-group-item d-md-flex w-100 justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="badge rounded-pill bg-success">公開中</span>
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


            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
            </div>

        </li>




        <li class="list-group-item d-md-flex w-100 justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="badge rounded-pill bg-success">公開中</span>
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


            <div class="col-md-3 text-md-end mt-3 mt-md-0">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
            </div>

        </li>



        <li class="list-group-item d-md-flex w-100 justify-content-between align-items-center row">

            <div class="col-md-9">
                <small class="text-muted">
                    3 days ago
                    <span class="badge rounded-pill bg-danger">非公開</span>
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


            <div class="col-md-3 text-md-end mt-3 mt-md-0">
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
