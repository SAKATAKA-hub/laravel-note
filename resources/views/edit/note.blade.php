

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
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item"><a href="#">マイページ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ノートの編集</li>
        </ol>
    </nav>
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
    <div class="preview_note_container display_note_container_orange"> <!-- (クラスからページカラーを指定できる) -->


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
            <button type="button" class="btn btn-primary"><i class="bi bi-plus-square-fill"></i> テキストボックスの挿入</button>
        </div>




        <div class=" edit_text_box">

            <div class="heading1">
                見出し1<strong>重要</strong>
            </div>

        </div>


        <div class="edit_btn_box">
            <div class="update_delete_btn">
                <button type="button" class="btn btn-outline-primary"><i class="bi bi-eraser-fill"></i> 修正</button>
                <button type="button" class="btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</button>
            </div>
            <button type="button" class="btn btn-primary"><i class="bi bi-plus-square-fill"></i> テキストボックスの挿入</button>
        </div>





        <div class=" edit_text_box">

            <div class="heading2">
                見出し2<strong>重要</strong>
            </div>

        </div>


        <div class="edit_btn_box">
            <div class="update_delete_btn">
                <button type="button" class="btn btn-outline-primary"><i class="bi bi-eraser-fill"></i> 修正</button>
                <button type="button" class="btn btn-outline-primary"><i class="bi bi-trash"></i> 削除</button>
            </div>
            <button type="button" class="btn btn-primary"><i class="bi bi-plus-square-fill"></i> テキストボックスの挿入</button>
        </div>




        <div class=" edit_text_box">

            <div class="heading3">
                見出し3<strong>重要</strong>
            </div>

        </div>




        <div class=" edit_text_box">

            <div class="normal_text">
                テキスト１テキスト１テキスト１<strong>重要</strong>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="important_text">
                重要な文章<strong>重要</strong>
            </div>

        </div>




        <div class=" edit_text_box">

            <div class="emphasized_text">
                強調する文章<strong>重要</strong>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="quote_text">
                テキスト２<br>テキスト２<br>テキスト２<strong>重要</strong>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="code_text">
                <div class="title">tatle.php</div>
                <div class="text">
                    テキスト２<br>テキスト２<br>テキスト２
                </div>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="link">
                <a href="#">リンク</a>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="image">
                <img src="common/img/sample.jpg" alt="">
                <p class="title">大きい写真</p>
            </div>

        </div>





        <div class=" edit_text_box">

            <div class="image_litle">
                <img src="common/img/sample.jpg" alt="">
                <p class="title">小さい写真</p>
            </div>

        </div>






        <div class=" edit_text_box">

            <div class="image_litle">
                <img src="common/img/sample.jpg" alt="">
                <p class="title">小さい写真</p>
            </div>

        </div>






        <div class=" edit_text_box">

            <div class="image_litle">
                <img src="common/img/sample.jpg" alt="">
                <p class="title">小さい写真</p>
            </div>

        </div>






        <div class=" edit_text_box">

            <div class="image_litle">
                <img src="common/img/sample.jpg" alt="">
                <p class="title">小さい写真</p>
            </div>

        </div>






    </div>

@endsection
