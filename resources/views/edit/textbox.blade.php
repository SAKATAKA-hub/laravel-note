

@extends('layouts.base')




@section('style')

    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

@endsection




@section('script')

    {{-- <script src="{{asset('js/edit_textbox_form.js')}}"></script> --}}

    <script>
        'use strict';

        //---------------------------------------------------------------
        // 関数：文字列をアッパーキャメルケースに変換

        function toUpperFirst(str) {
            return str.charAt(0).toUpperCase() + str.substring(1).toLowerCase();
        }
        //---------------------------------------------------------------




        //---------------------------------------------------------------
        // 基本設定
        //---------------------------------------------------------------
        // テキストボックスの種類グループ
        const textboxCaseGroups = ['heading','text','link','image'];

        // テキストボックスの種類を選択するselect要素
        const inputTextBoxCase = document.getElementById('inputTextBoxCase');

        // 入力域
        const inputBoxs = document.querySelectorAll('.input_box');

        // 編集中テキストボックス表示領域
        const editingBoxs = document.querySelectorAll('.editing_box');




        //---------------------------------------------------------------
        // 挿入するテキストボックスの種類を選択
        //---------------------------------------------------------------
        inputTextBoxCase.onchange = ()=> {

            // <-- 選択したテキストボックスの種類 -->
            let textboxCase = inputTextBoxCase.value;

            // <-- 選択したテキストボックスの種類グループ -->
            let textboxCaseGroup ="";
            textboxCaseGroups.forEach((group)=>{

                if( textboxCase.includes(group) ){ textboxCaseGroup = toUpperFirst(group);}

            });


            // <-- 入力域の表示切替 -->
            inputBoxs.forEach( (inputBox)=> {

                // 全て非表示
                inputBox.className = "input_box hidden";

                // 入力域の変更
                if(inputBox.id === 'input'+textboxCaseGroup)
                {
                    inputBox.classList.remove('hidden'); //表示
                    inputBox.querySelector('input[name="textbox_case_name"]').value = textboxCase; //インプットタグのvalueにテキストボックスの種類を代入
                }

            });




            // <-- プレビュー域の表示変更 -->
            editingBoxs.forEach( (editingBox)=> {

                // 全て非表示
                editingBox.className = "editing_box hidden";

                // 表示
                if(editingBox.id === 'editing'+textboxCaseGroup)
                {
                    editingBox.classList.remove('hidden');
                    editingBox.classList.add(textboxCase);
                }

            });


        }








        //---------------------------------------------------------------
        // 入力内容をプレビューに反映させる
        //---------------------------------------------------------------
        textboxCaseGroups.forEach( (group)=>{

            // <-- main_valueの入力 -->
            let inputMain = document.getElementById('input'+toUpperFirst(group)+'MainValue'); //main入力要素
            if(inputMain !== null){ //選択したテキストボックスの種類に、main_valueの入力が存在しなければ、処理なし

                inputMain.onchange = ()=>{

                    if(group === 'link')
                    {
                        let editLink = document.getElementById('editing'+toUpperFirst(group)).querySelector('a');
                        editLink.href = inputMain.value;//main_valueの入力値をプレビューに表示

                    }else{

                        let editMain = document.getElementById('editing'+toUpperFirst(group)).querySelector('.mainValue');
                        // editMain.textContent = inputMain.value;//main_valueの入力値をプレビューに表示

                        //改行処理
                        let inputMainValue = inputMain.value;
                        let result = '';
                        while(inputMainValue !== result) {
                            inputMainValue = inputMainValue.replace('\n','<br>');
                            result = inputMainValue.replace('\n','<br>');
                        }

                       editMain.innerHTML = inputMainValue;//main_valueの入力値をプレビューに表示

                    }

                };
            }


            // <-- sub_valueの入力 -->
            let inputSub = document.getElementById('input'+toUpperFirst(group)+'SubValue'); //sub入力要素
            if(inputSub !== null){ //選択したテキストボックスの種類に、sub_valueの入力が存在しなければ、処理なし

                inputSub.onchange = ()=>{

                    if(group === 'image')
                    {
                        let editSub = document.getElementById('editing'+toUpperFirst(group)).querySelector('.subValue');
                        editSub.textContent = inputSub.value; //sub_valueの入力値をプレビューに表示
                    }

                    if(group === 'link')
                    {
                        let editLink = document.getElementById('editing'+toUpperFirst(group)).querySelector('a');
                        editLink.textContent = inputSub.value; //sub_valueの入力値をプレビューに表示
                    }

                };
            }

        });

        //---------------------------------------------------------------
        // アップロード画像の表示
        //---------------------------------------------------------------
        //
        // 添付画像の読み込み
        // <input type="file" id="myImage" accept="image/*"
        //    onchange="setImage(this);" onclick="this.value = '';">
        // <img id="previewImage">
        //---------------------------------------------------------------
        function setImage(target) {
            var reader = new FileReader();
            reader.onload = function (e) {

                document.getElementById("previewImage").setAttribute('src', e.target.result);

            }
            reader.readAsDataURL(target.files[0]);
        };

    </script>

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
    <div class="preview_note_container display_note_container_{{$note->color}}"> <!-- (クラスからページカラーを指定できる) -->




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




        <!-- 編集中テキストボックスの表示(初めてのテキストボックスの挿入) -->
        @if ($note->textboxes->count() === 0)
            @include('includes.main_container.editing_textbox')
        @endif


        <!-- テキストボックスの表示 -->
        @foreach ($note->textboxes as $textbox)
            <!-- 編集中テキストボックスの表示 -->
            @if ($textbox->order == $order)
                @include('includes.main_container.editing_textbox')
            @endif

            <!-- 保存済みテキストボックスの表示(編集中のテキストボックスは非表示) -->
            @if ( !( isset($edit_textbox) && ($textbox->order == $order) ) )
                @include('includes.main_container.textbox_cases')
            @endif


            <!-- 編集中テキストボックスの表示(末尾表示) -->
            @if ( $loop->last && (($textbox->order +1) == $order) )
                @include('includes.main_container.editing_textbox')
            @endif

        @endforeach




    </div>

@endsection
