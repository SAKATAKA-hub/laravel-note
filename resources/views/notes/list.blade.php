
@extends('layouts.base')




@section('style')

    <!-- side_container.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">
    <style>
        .main_container .note_master_only{ display: inline-block;}
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


        @foreach ($notes as $note)
            <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

                <div class="col-md-9">
                    <small class="text-muted">

                        @if ($note->publishing)
                        <span class="note_master_only badge rounded-pill bg-success">公開中</span>
                        @else
                        <span class="note_master_only badge rounded-pill bg-danger">非公開</span>
                        @endif

                        {{$note->updated_at}}

                    </small>


                    <div class="">
                        <a href="{{route('show',$note->id)}}"  class="">
                            <h3 class="text-primary mt-2">{{$note->title}}</h3>
                        </a>
                    </div>


                    <small class="text-muted">
                        <i class="bi bi-tag-fill"></i>

                        @foreach ($note->tags_array as $tag)
                        <a href="">{{$tag}}</a>
                        @endforeach
                    </small>

                </div>


                <div class="note_master_only col-md-3 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                    <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
                </div>

            </li>
        @endforeach


    </ul>




    <!-- pagenation -->
    {{ $notes->links('includes.pagination.oliginal') }}


@endsection
