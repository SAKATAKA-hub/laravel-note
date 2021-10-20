

@extends('layouts.base')




@section('style')
    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

    <!-- note_master_only -->
    @if ( $note && Auth::check() && (Auth::user()->id == $mypage_master->id) )
        <style>
            .main_container .note_master_only{ display: block;}
        </style>
    @endif

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

        // 公開設定ボタンの表示
        const publishingInput = document.getElementById('inputPublishing');
        const publishingLabel = document.querySelector('label[for="inputPublishing"]');

        // 公開日予約入力の表示
        const releaseDatetimeInput = document.getElementById('inputReleaseDatetime');
        const releaseDatetimeLabel = document.querySelector('label[for="inputReleaseDatetime"]');


        publishingInput.onchange = ()=> {

            if( publishingInput.checked )
            {
                publishingLabel.textContent = '公開';
                publishingLabel.classList.add('text-primary');
                publishingLabel.classList.remove('text-secondary');

                releaseDatetimeInput.value = null;
                releaseDatetimeInput.readOnly = true;
                releaseDatetimeInput.classList.add('text-secondary');
                releaseDatetimeLabel.classList.add('text-secondary');

            }
            else{
                publishingLabel.textContent = '非公開';
                publishingLabel.classList.remove('text-primary');
                publishingLabel.classList.add('text-secondary');

                releaseDatetimeInput.readOnly = false;
                releaseDatetimeInput.classList.remove('text-secondary');
                releaseDatetimeLabel.classList.remove('text-secondary');

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
        ノートの基本情報編集
    @endif

@endsection



@section('main.breadcrumb')

    @if (!$note)
        {{ Breadcrumbs::render('create_note_title', $mypage_master) }}
    @else
        {{ Breadcrumbs::render('edit_note_title', $mypage_master,$note) }}
    @endif

@endsection








@section('main.side_container')

    @include('includes.side_container.edit.note_title')

    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.top_container')


    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        @if (!$note)
            ノートの新規作成
        @else
            ノートの基本情報編集
        @endif
    </h2>


    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>


@endsection




@section('main.center_container')


    <!-- ノート表示域 -->
    @if (!$note)<!-- (クラスからページカラーを指定できる) -->
        <div class="preview_note_container display_note_container_" id="previewContainer">
    @else
        <div class="{{'preview_note_container display_note_container_'.old('color',$note->color)}}" id="previewContainer">
    @endif




        <div class="editing_textbox">

            <div class="alert alert-danger text-center fs-5" role="alert">
                ・・・  ノート基本情報の編集中  ・・・
            </div>




            <div class="title_box">
                <small class="d-flex">

                    <!-- 公開設定 (マイページ管理者ログイン時以外は非表示)-->
                    @if ($note && $note->chake_publishing)
                    <span class="note_master_only badge rounded-pill bg-success me-3">公開中</span>
                    @else
                    <span class="note_master_only badge rounded-pill bg-danger  me-3">非公開</span>
                    @endif

                    <!-- 作成日時・公開日時・更新日時 -->
                    <div>{{!$note? \Carbon\Carbon::parse('tomorrow')->format('作成日:Y年m月d日').' 00:00': $note->time_text}}</div>

                </small>


                <!-- 投稿タイトル -->
                <h3 class="title" id="noteTitle" >{{!$note? '': old('title', $note->title)}}</h3>


                <!-- 関連タグ -->
                <small class="d-flex">
                    <i class="bi bi-tag-fill me-2"></i>
                    @if (!$note)
                        <div id="noteTag"><!-- ※選択したタグが表示されます。 --></div>
                    @else
                        <div id="noteTag">
                            <?php $tags_array = old('tags')? old('tags'): $note->tags_array; ?><!-- (エラーがあれば、oldの返り値) -->
                            @foreach ($tags_array as $tag)
                            <span>{{$tag}}　</span>
                            @endforeach
                        </div>
                    @endif
                </small>
            </div>


        </div>




        <!-- 保存済みテキストボックスの表示 -->
        @if ($note)

            @foreach ($note->textboxes as $textbox)

                @include('includes.main_container.textbox_cases')

            @endforeach

        @endif




    </div>

@endsection
