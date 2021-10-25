
@extends('layouts.base')




@section('style')

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

    <!-- modal -->>
    {{-- <script src="{ aseet('js/includes/modal.js') }}"></script> --}}
    <script>
        'use strict';
        function modalInput(target)
        {
            let input = document.getElementById('modalInputElement');
            input.value = target.value;
            console.log(input.value);
        }
    </script>

@endsection






@section('title',$mypage_master->name.'さんのマイページ')





@section('main.breadcrumb')


    @if (isset($seach_heading))
        {{ Breadcrumbs::render('mypage_seach', $mypage_master, $seach_heading) }}<!-- 検索ページ -->
    @else
        {{ Breadcrumbs::render('mypage_top', $mypage_master) }}<!-- マイページTOP -->
    @endif

@endsection




@section('main.side_container')

    @include('includes.side_container.lists')

@endsection








@section('main.top_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        {{$mypage_master->name.'さんのマイページ'}}
    </h2>


@endsection


@section('main.center_container')


    <!-- 見出しの表示 -->
    <h5 class="d-flex justify-content-between align-items-center mb-3" style="padding-right:.5em">

        <!-- 通常の見出し -->
        @if (!isset($seach_heading))

            <!-- マイページ管理者ログイン中 -->
            @if ( Auth::check() && (Auth::user()->id == $mypage_master->id) )
                <div class="fs-3"><i class="bi bi-file-earmark-text"></i> 新規作成順ノート一覧</div>

            <!-- 上記以外 -->
            @else
                <div class="fs-3"><i class="bi bi-file-earmark-text"></i> 公開中ノート一覧</div>
            @endif


        <!-- 検索機能を利用した時の見出し -->
        @else
            <div class="fs-3"><i class="bi bi-file-earmark-text"></i>{{$seach_heading}}</div>
        @endif
        <a href="{{route('create_note_title',$mypage_master)}}" class="note_master_only btn btn-lg btn-primary" style="font-size:.8em;">
            <i class="bi bi-file-earmark-plus"></i> 新規ノート作成
        </a>
    </h5>


    <!-- notes list -->
    <ul class="list-group mb-3">


        @forelse ($notes as $note)
            <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

                <small class="text-muted d-flex">

                    <!-- 公開設定 (マイページ管理者ログイン時以外は非表示)-->
                    @if ($note->chake_publishing)
                    <p class="note_master_only badge rounded-pill bg-success me-3">公開中</p>
                    @else
                    <p class="note_master_only badge rounded-pill bg-danger me-3">非公開</p>
                    @endif

                    <!-- 作成日時・公開日時・更新日時 -->
                    <div>{{$note->time_text}}</div>

                </small>



                <div class="col-md-9">

                    <!-- 投稿タイトル -->
                    <div class="">
                        <a href="{{route( 'note',$note )}}"  class="text-primary">
                            <h3 class="">{{$note->title}}</h3>
                        </a>
                    </div>


                     <!-- 関連タグ -->
                    <small class="text-muted">
                        <i class="bi bi-tag-fill"></i>

                        @foreach ($note->tags_array as $tag)
                            <form class="d-inline" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                                <input type="hidden" name="list_type" value="tag"> <!-- name="list_type" -->
                                <input type="hidden" name="seach_value" value="{{$tag}}"> <!--name="seach_value" -->
                                <button class="" type="submit">{{$tag}}</button>
                            </form>
                        @endforeach

                    </small>

                </div>


                <div class="note_master_only col-md-3 text-md-end mt-3 mt-md-0">
                    <a href="{{route('edit_note', compact('note') )}}" class="btn btn-outline-primary">
                        <i class="bi bi-eraser-fill"></i> 編集
                    </a>

                    {{-- <button class="btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</button> --}}

                    <button class="btn btn-outline-primary" type="button" value="{{ $note->id }}"  data-bs-toggle="modal" data-bs-target="#centerModal" onclick="modalInput(this)">
                        <i class="bi bi-trash"></i>削除
                    </button>

                </div>

            </li>
        @empty
            <li class="list-group-item w-100 text-center p-5 fs-4 text-secondary">
                投稿はありません
            </li>
        @endforelse


    </ul>




    <!-- pagenation -->
    {{ $notes->links('includes.pagination.oliginal') }}




    <!-- Modal(データ削除モーダル) -->
    <form action="{{route('destroy_note',$mypage_master)}}" method="POST">
        @method('DELETE')
        @csrf
        <input type="hidden" name="note_id" value="aaa" id="modalInputElement">

        @php
            $modal = [
                'title' => 'ノートの削除',
                'body' => 'ノートを1件削除します。\nよろしいですか？',
                'yes_btn' => '削除',
            ];
        @endphp
        @include('includes.modal')
    </form>
    {{--
        * 削除ボタン
        <button class=""  type="button" value="{{ $note }}" data-bs-toggle="modal" data-bs-target="#centerModal" onclick="deletModalInput(this)">
            削除
        </button>

        * js読込み (deletModalInput関数)
        <script src="{ aseet('js/includes/modal.js') }}"></script>
    --}}


@endsection
