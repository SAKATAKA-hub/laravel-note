

@extends('layouts.base')




@section('style')

    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

    <style>
        .note_master_only{ display: none;}
    </style>
@endsection




@section('script')

    <script src="{{asset('js/edit_note_title_form.js')}}"></script>

@endsection







@section('title','')





@section('main.breadcrumb')

    {{ Breadcrumbs::render('edit_note', $mypage_master) }}

@endsection








@section('main.side_container')

    <h2 class="mt-5  mb-4 text-secondary">編集の操作内容をプレビュー枠内のボタンより選択してください。</h2>

    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.center_container')


    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        ノートの編集
    </h2>




    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>


    <!-- ノート表示域 -->
    <div class="preview_note_container display_note_container_{{$note->color}}"> <!-- (クラスからページカラーを指定できる) -->

        <!-- タイトルボックスの表示 -->
        <div class=" edit_text_box">

            <div class="title_box">
                {{-- 投稿日 --}}
                <small>{{$note->created_at}}</small>

                {{-- タイトル --}}
                <h3 class="title">{{$note->title}}</h3>

                {{-- タグ --}}
                <small class="d-flex">
                    <i class="bi bi-tag-fill me-2"></i>
                    @foreach ($note->tags_array as $tag)
                    <span>{{$tag}}　</span>
                    @endforeach
                </small>
            </div>

        </div>


        <div class="edit_btn_box">
            <div class="update_delete_btn">
                <a href="{{route('edit_note_title',$note)}}" class="btn btn-outline-primary"><i class="bi bi-eraser-fill"></i> 基本情報の修正</a>
                {{-- <a href="" class="btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</a> --}}
            </div>

            <a href="{{route('create_textbox',compact('note')+['order'=> 1])}}"  class="btn btn-primary">
                <i class="bi bi-plus-square-fill"></i> テキストボックスの挿入
            </a>
        </div>




        <!-- テキストボックスの表示 -->
        @foreach ($note->textboxes as $textbox)

            <div class=" edit_text_box">
                @include('includes.main_container.textbox_cases')
            </div>

            <div class="edit_btn_box">
                <div class="update_delete_btn">
                    <a href="{{route('edit_textbox',$textbox)}}" class="btn btn-outline-primary"><i class="bi bi-eraser-fill"></i> 修正</a>

                    <form method="POST" action="{{route('destroy_textbox',$note)}}">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="textbox_id" value="{{$textbox->id}}"> <!-- テキストボックスのID -->
                        <input type="hidden" name="order" value="{{$textbox->order}}"> <!-- テキストボックスの採番 -->
                        <button type="submit" class="btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</button>
                    </form>

                </div>

                <p>{{$textbox->order}}</p>

                <a href="{{route('create_textbox',compact('note')+['order'=> $textbox->order+1])}}"  class="btn btn-primary">
                    <i class="bi bi-plus-square-fill"></i> テキストボックスの挿入
                </a>
            </div>

        @endforeach


    </div>

@endsection
