
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

    {{ Breadcrumbs::render('note', $mypage_master, $note) }}

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

                <!-- 公開設定 (マイページ管理者ログイン時以外は非表示)-->
                @if ($note->chake_publishing)
                <span class="note_master_only badge rounded-pill bg-success me-3"style="height:2em">公開中</span>
                @else
                <span class="note_master_only badge rounded-pill bg-danger  me-3"style="height:2em">非公開</span>
                @endif

                <!-- 作成日時・公開日時・更新日時 -->
                <div>{{$note->time_text}}</div>

            </small>


            <!-- 投稿タイトル -->
            <h3 class="title ">{{$note->title}}</h3>


            <!-- 関連タグ -->
            <small class="d-flex">
                <i class="bi bi-tag-fill me-2"></i>

                @foreach ($note->tags_array as $tag)
                    <form class="d-inline" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                        <input type="hidden" name="list_type" value="tag"> <!-- name="list_type" -->
                        <input type="hidden" name="seach_value" value="{{$tag}}"> <!--name="seach_value" -->
                        <button class="" type="submit">{{$tag}}</button>
                    </form>
                @endforeach

            </small>


            <div class="mt-3 d-flex">
                <button class="btn btn-outline-primary" onclick="window.open('{{route('print',$note)}}');">
                    <i class="bi bi-printer"></i> 印刷
                </button>
            </div>

        </div>






        <!-- textboxs -->
        @foreach ($note->textboxes as $textbox)

            @include('includes.main_container.textbox_cases')

        @endforeach

    </div>


@endsection
