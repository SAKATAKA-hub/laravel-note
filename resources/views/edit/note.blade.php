

@extends('layouts.base')




@section('style')

    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">

    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

    <!-- note_master_only -->
    @if ( Auth::check() && (Auth::user()->id == $mypage_master->id) )
        <style>
            .main_container .note_master_only{ display: block;}
        </style>
    @endif

    <style>
        .note_master_only{ display: none;}
    </style>
@endsection




@section('script')

    <script src="{{asset('js/edit_note_title_form.js')}}"></script>

@endsection







@section('title',$note->title.'"の編集')





@section('main.breadcrumb')

    {{ Breadcrumbs::render('edit_note',$mypage_master,$note) }}

@endsection








@section('main.side_container')

    <a href="{{route('edit_note_title',$note)}}" class="btn btn-outline-primary d-block mb-2">
        <span class="">基本情報修正</span>
    </a>


    @include('includes.side_container.edit.back_buttons')

@endsection







@section('main.top_container')

    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-2">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        "{{$note->title}}"の編集
    </h2>

    <h4 class="mb-2 text-secondary">編集の操作内容をプレビュー枠内のボタンより選択してください。</h4>

    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>

@endsection




@section('main.center_container')


    <!-- ノート表示域 -->
    <div class="preview_note_container display_note_container_{{$note->color}}"> <!-- (クラスからページカラーを指定できる) -->




        <!-- タイトルボックスの表示 -->
        <div class="edit_textbox d-flex align-items-center mb-2">


            <div class="title_box w-75">
                <!-- 投稿日 -->
                <small class="d-flex">

                    <!-- 公開設定 (マイページ管理者ログイン時以外は非表示)-->
                    @if ($note->chake_publishing)
                    <span class="note_master_only badge rounded-pill bg-success me-3" style="height:2em">公開中</span>
                    @else
                    <span class="note_master_only badge rounded-pill bg-danger  me-3" style="height:2em">非公開</span>
                    @endif

                    <!-- 作成日時・公開日時・更新日時 -->
                    <div>{{$note->time_text}}</div>

                </small>


                <!-- タイトル -->
                <h3 class="title">{{$note->title}}</h3>

                <!-- タグ -->
                <small class="d-flex">
                    <i class="bi bi-tag-fill me-2"></i>
                    @foreach ($note->tags_array as $tag)
                    <span>{{$tag}}　</span>
                    @endforeach
                </small>
            </div>


            <div class="w-25 ms-2 text-center">

                <a href="{{route('create_textbox',compact('note')+['order'=> 1])}}"  class="btn btn-primary d-block">
                    <i class="bi bi-plus-square-fill d-none d-md-inline"></i>
                    <span>挿入</span>
                </a>

            </div>



        </div>




        <!-- テキストボックスの表示 -->
        @foreach ($note->textboxes as $textbox)


            <div class=" edit_textbox d-flex align-items-center  mb-2">


                <div class="w-75">
                    @include('includes.main_container.textbox_cases')
                </div>


                <div class="w-25 ms-2">

                    <a href="{{route('edit_textbox',compact('note','textbox'))}}" class="btn btn-outline-primary d-block mb-2">
                        <i class="bi bi-eraser-fill d-none d-md-inline"></i>
                        <span>修正</span>
                    </a>

                    <form method="POST" action="{{route('destroy_textbox',$note)}}">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="textbox_id" value="{{$textbox->id}}"> <!-- テキストボックスのID -->
                        <input type="hidden" name="order" value="{{$textbox->order}}"> <!-- テキストボックスの採番 -->
                        <button type="submit" class="btn btn-outline-primary w-100 mb-2">
                            <i class="bi bi-trash d-none d-md-inline"></i>
                            <span>削除</span>
                        </button>
                    </form>

                    <a href="{{route('create_textbox',compact('note')+['order'=> $textbox->order+1])}}" class="btn btn-primary d-block mb-2">
                        <i class="bi bi-plus-square-fill d-none d-md-inline"></i>
                        <span>挿入</span>
                    </a>

                </div>


            </div>





        @endforeach


    </div>

@endsection
