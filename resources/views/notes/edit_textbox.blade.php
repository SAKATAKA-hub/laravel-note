

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

    <script src="{{asset('js/edit_textbox_form.js')}}"></script>

@endsection








@section('title','マイノート編集(テキストボックス編集)')





@section('main.breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item"><a href="#">マイページ</a></li>
            <li class="breadcrumb-item"><a href="#">マイノート編集(TOP)</a></li>
            <li class="breadcrumb-item active" aria-current="page">テキストボックス編集</li>
        </ol>
    </nav>
@endsection








@section('main.side_container')
    @include('includes.side_container.edit.textbox')

    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.center_container')


    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>


    <!-- ノート表示域 -->
    <div class="preview_note_container display_note_container_orange"> <!-- (クラスからページカラーを指定できる) -->



        <div class="">

            <div class="title_box">
                <p>0000年00月00日更新</p>
                <h2 class="title">タイトルsss</h2>
                <p><i class="bi bi-tag-fill"></i>
                    <a href="">laravel</a>
                    <a href="">テスト</a>
                </p>
            </div>

        </div>




        <div class="">

            <div class="heading1">
                <p class="text">見出し1<strong>重要</strong></p>
            </div>

        </div>





        <div class="editing_text_box">

            <div class="alert alert-danger text-center fs-5" role="alert">
                ・・・  編集中テキストボックス  ・・・
            </div>




            <div class="editing heading1  hidden">
                <p class="text"><strong>例）</strong>見出し1が表示されます。</p>
            </div>


            <div class="editing link hidden">
                <a class="text" href="#">リンク</a>
            </div>


            <div class="editing image hidden">
                <p class="subval title">画像タイトル</p>
                <img id="previewImage">
            </div>



        </div>







    </div>

@endsection
