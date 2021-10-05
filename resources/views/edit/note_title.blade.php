

@extends('layouts.base')




@section('style')
    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

    <style>
        .display_note_container_ .text_box{
            display: none;
        }
    </style>
@endsection




@section('script')

    {{-- <script src="{{asset('js/edit_note_title_form.js')}}"></script> --}}

    <script>
        'use strict';
        // ----------------------------------------------
        // 公開設定スイッチの切り替え
        // ----------------------------------------------

        const publishingInput = document.getElementById('inputPublishing');
        const publishingLabel = document.querySelector('label[for="inputPublishing"]');

        publishingInput.onchange = ()=> {

            if( publishingInput.checked )
            {
                publishingLabel.textContent = '公開';
                publishingLabel.classList.add('text-primary');
                publishingLabel.classList.remove('text-secondary');
                // 'text-primary'
            }
            else{
                publishingLabel.textContent = '非公開';
                publishingLabel.classList.remove('text-primary');
                publishingLabel.classList.add('text-secondary');
            }

        };

        // ----------------------------------------------
        // プレビュー画面へのレンダリング
        // ----------------------------------------------

        // タイトル
        const inputNoteTitle = document.getElementById('inputNoteTitle');
        const noInputNoteTitle = ''
        // document.getElementById('noteTitle').textContent = noInputNoteTitle;

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
        const noInputTags = ''
        // document.getElementById('noteTag').textContent =  noInputTags;

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
                // tags[0].required = ""; //requiredを無効にする。
            }
            else
            {
                document.getElementById('noteTag').textContent =  noInputTags;
                // tags[0].required = "required"; //requiredを有効にする。
            }
        }
    </script>

@endsection







@section('title')

    @if (!$note)
        ノートの新規作成
    @else
        基本情報編集
    @endif

@endsection



@section('main.breadcrumb')

    @if (!$note)
        {{ Breadcrumbs::render('create_note_title', $mypage_master) }}
    @else
        {{ Breadcrumbs::render('edit_note_title', $mypage_master) }}
    @endif

@endsection








@section('main.side_container')

    @include('includes.side_container.edit.note_title')
    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.center_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        @if (!$note)
            ノートの新規作成
        @else
            基本情報編集
        @endif
    </h2>


    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>


    <!-- ノート表示域 -->
    @if (!$note)<!-- (クラスからページカラーを指定できる) -->
        <div class="preview_note_container display_note_container_" id="previewContainer">
    @else
        <div class="{{'preview_note_container display_note_container_'.old('color',$note->color)}}" id="previewContainer">
    @endif




        <div class="editing_text_box">

            <div class="alert alert-danger text-center fs-5" role="alert">
                ・・・  ノート基本情報の編集中  ・・・
            </div>

            <div class="title_box">
                {{-- 投稿日 --}}
                <small>{{!$note? '0000-00-00 00:00:00': $note->created_at}}</small>

                {{-- タイトル --}}
                <h3 class="title"  id="noteTitle">{{!$note? '': old('title', $note->title)}}</h3>

                {{-- タグ --}}
                <small class="d-flex">
                    <i class="bi bi-tag-fill me-2"></i>
                    @if (!$note)
                        <div id="noteTag"><!-- ※選択したタグが表示されます。 --></div>
                    @else
                        <div id="noteTag">
                            <?php $tags_array = old('tags')? old('tags'): $note->tags_array; ?><!-- (エラーがあれば、oldの返り値) -->
                            @foreach ($tags_array as $tag)
                            {{$tag}}　
                            @endforeach
                        </div>
                    @endif
                </small>

                {{-- <div class="d-flex">
                    <i class="bi bi-tag-fill" style="margin-right:.5em"></i>
                    <div id="noteTag"><!-- ※選択したタグが表示されます。 --></div>
                </div> --}}
            </div>

        </div>




        <div class="text_box">


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
