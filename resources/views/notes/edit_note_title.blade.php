

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


    <script>
        'use strict';



        // ----------------------------------------------
        // プレビュー画面へのレンダリング
        // ----------------------------------------------

        // タイトル
        const inputNoteTitle = document.getElementById('inputNoteTitle');
        const noInputNoteTitle = '※タイトルを入力してください。'
        document.getElementById('noteTitle').textContent = noInputNoteTitle;

        inputNoteTitle.onchange = ()=>{
            if(inputNoteTitle.value !== '')
            {
                document.getElementById('noteTitle').textContent = inputNoteTitle.value;
            }
            else
            {
                document.getElementById('noteTitle').textContent = noInputNoteTitle;
            }
        };


        // テーマカラー
        const inputNoteColor = document.getElementById('inputNoteColor');
        inputNoteColor.onchange = ()=>{
            const previewContainer = document.getElementById('previewContainer');
            previewContainer.className = 'preview_note_container display_note_container_'+inputNoteColor.value;
        };


        // タグ
        const tags = document.querySelectorAll('input[name="tags[]"]');
        const newTags = document.getElementById('newTags');
        const noInputTags = '※タグを選択してください。'
        document.getElementById('noteTag').textContent =  noInputTags;

        tags.forEach( changeTag => {
            changeTag.onchange = ()=>{

                changeTags();　// *changeTags関数

            };
        } );


        // *changeTags関数
        function changeTags()
        {
            const tagsArray = [];

            // チェックボックスの入力値
            tags.forEach( tag => {

                // console.log(tag.value);
                if(tag.checked)
                {
                    tagsArray.push(tag.value);
                }
            } );

            //インプットボックスの入力値
            if(newTags.value!=='')
            {
                tagsArray.push( newTags.value.replace(/ /g, '　') );
            }


            //入力値の挿入
            if(tagsArray.length)
            {
                document.getElementById('noteTag').textContent =  tagsArray.join('　');
                tags[0].required = ""; //requiredを無効にする。
            }
            else
            {
                document.getElementById('noteTag').textContent =  noInputTags;
                tags[0].required = "required"; //requiredを有効にする。
            }
        }



    </script>

@endsection







@section('title','マイノート編集(基本情報編集)')





@section('main.breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-fill"></i>home</a></li>
            <li class="breadcrumb-item"><a href="#">マイページ</a></li>
            <li class="breadcrumb-item"><a href="#">マイノート編集(TOP)</a></li>
            <li class="breadcrumb-item active" aria-current="page">基本情報編集</li>
        </ol>
    </nav>
@endsection








@section('main.side_container')

    @include('includes.side_container.edit.note_title')
    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.center_container')


    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>


    <!-- ノート表示域 -->
    <div class="preview_note_container display_note_container_green" id="previewContainer"> <!-- (クラスからページカラーを指定できる) -->



        <div class="editing_text_box">

            <div class="alert alert-danger text-center fs-5" role="alert">
                ・・・  ノート基本情報の編集中  ・・・
            </div>

            <div class="title_box">
                <p>0000年00月00日更新</p>
                <h2 class="title" id="noteTitle">タイトルsss</h2>
                <div class="d-flex">
                    <i class="bi bi-tag-fill" style="margin-right:.5em"></i>
                    <div id="noteTag">
                        <a href="">laravel</a>
                        <a href="">テスト</a>
                    </div>
                </div>
            </div>

        </div>




        <div class="">


            <div class="heading1">
                <p class="text"><strong>Sample</strong> サンプルテキストの表示</p>
            </div>

            <div class="normal_text">
                <strong>サンプルテキスト</strong><br>
                サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル
                サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル
                サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル
                サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル
                サンプルサンプルサンプルサンプルサンプルサンプルサンプルサンプル

            </div>

            <div class="emphasized_text">
                ＊この文章はサンプルです。
            </div>


        </div>





        {{-- <div class="editing_text_box">

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
                <img id="previewImage">
                <p class="subval title">画像タイトル</p>
            </div>



        </div> --}}







    </div>

@endsection
