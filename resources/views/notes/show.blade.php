
@extends('layouts.base')




@section('style')

    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">

    <!-- side_container.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">

    <!-- note_master_only -->
    @if ( Auth::check() && (Auth::user()->id == $mypage_master->id) )
        <style>
            .main_container .note_master_only{ display: block;}
        </style>
    @endif

@endsection




@section('script')


@endsection







@section('title',$note->title)





@section('main.breadcrumb')

    {{ Breadcrumbs::render('show', $mypage_master, $note) }}

@endsection




@section('main.side_container')

    @include('includes.side_container.lists')

@endsection




@section('main.center_container')

    <!-- ノート表示域 -->
    <div class="display_note_container_{{$note->color}}"> <!-- (クラスからページカラーを指定できる) -->




        <!-- title_box -->
        <div class="title_box">
            <small class="d-flex">
                @if ($note->publishing)
                <span class="note_master_only badge rounded-pill bg-success me-3">公開中</span>
                @else
                <span class="note_master_only badge rounded-pill bg-danger  me-3">非公開</span>
                @endif
                <span>{{$note->created_at}}</span>
            </small>


            <h3 class="title">{{$note->title}}</h3>


            <small class="d-flex">

                <i class="bi bi-tag-fill me-2"></i>
                @foreach ($note->tags_array as $tag)
                <a href="">{{$tag}}　</a>
                @endforeach

            </small>


            <div class="mt-3">
                <button class="note_master_only btn btn-outline-primary"><i class="bi bi-eraser-fill"></i> 編集</button>
                <button class="note_master_only btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</button>

                <a class="btn btn-outline-primary" href="{{route('print',$note)}}">
                    <i class="bi bi-printer"></i> 印刷
                </a>
            </div>

        </div>







        <!-- textboxs -->
        @foreach ($note->textboxs as $textbox)

            @include('includes.main_container.textbox_cases')

        @endforeach

    </div>


@endsection
