
@extends('layouts.base')




@section('title','マイノートの表示')




@section('style')

    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">

@endsection




@section('script')


@endsection








@section('main.breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item"><a href="#">マイページ</a></li>
            <li class="breadcrumb-item active" aria-current="page">マイノートの表示</li>
        </ol>
    </nav>
@endsection




@section('main.side_container')

    @include('includes.side_container')

@endsection




@section('main.center_container')
    <!-- ノート表示域 -->
    <div class="display_note_container_orange"> <!-- (クラスからページカラーを指定できる) -->




        <div class="title_box">
            <p>0000年00月00日更新<span class="badge rounded-pill bg-success">公開中</span></p>

            <h2 class="title">タイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトルタイトル</h2>

            <p><i class="bi bi-tag-fill"></i>
                <a href="">laravel</a>
                <a href="">テスト</a>
            </p>


            <div class="operation_box">
                <button class="btn btn-outline-secondary"><i class="bi bi-eraser-fill"></i>編集</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>削除</button>
                <button class="btn btn-outline-secondary"><i class="bi bi-trash"></i>印刷</button>
            </div>

        </div>



        <div class="heading1">
            見出し1<strong>重要</strong>
        </div>

        <div class="heading2">
            見出し2<strong>重要</strong>
        </div>

        <div class="heading3">
            見出し3<strong>重要</strong>
        </div>

        <div class="normal_text">
            テキスト１テキスト１テキスト１<strong>重要</strong>
        </div>

        <div class="important_text">
            重要な文章<strong>重要</strong>
        </div>

        <div class="emphasized_text">
            強調する文章<strong>重要</strong>
        </div>

        <div class="quote_text">
            テキスト２<br>テキスト２<br>テキスト２<strong>重要</strong>
        </div>

        <div class="code_text">
            <div class="title">tatle.php</div>
            <div class="text">
                テキスト２<br>テキスト２<br>テキスト２
            </div>
        </div>

        <div class="link">
            <a href="#">リンク</a>
        </div>


        <div class="image">
            <img src="common/img/sample.jpg" alt="">
            <p class="title">大きい写真</p>
        </div>

        <div class="image_litle">
            <img src="common/img/sample.jpg" alt="">
            <p class="title">小さい写真</p>
        </div>
        <div class="image_litle">
            <img src="common/img/sample.jpg" alt="">
            <p class="title">小さい写真</p>
        </div>
        <div class="image_litle">
            <img src="common/img/sample.jpg" alt="">
            <p class="title">ddd</p>
        </div>
        <div class="image_litle">
            <img src="common/img/sample.jpg" alt="">
            <p class="title">小さい写真</p>
        </div>


        <div class="heading1">見出し1</div>
        <div class="text">テキストテキストテキスト</div>

    </div>


@endsection
