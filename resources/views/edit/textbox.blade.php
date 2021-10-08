

@extends('layouts.base')




@section('style')

    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

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



        <!-- タイトルボックスの表示 -->
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


        <!-- テキストボックスの表示 -->
        @foreach ($note->textboxes as $textbox)


            <!-- 編集中テキストボックスの表示 -->
            @if ($textbox->order == $order)
            <div class="editing_text_box">

                {{-- <div class="alert alert-danger text-center fs-5" role="alert">
                    ・・・  編集中テキストボックス  ・・・
                </div> --}}




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
            @endif


            @include('includes.main_container.textbox_cases')

        @endforeach




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
