

@extends('layouts.base')



@section('style')

    <!-- token -->
    <meta name="token" content="{{ csrf_token() }}">

    <!-- route -->
    {{-- <meta name="json_note" content="{{route('json_note',$note)}}"> --}}
    <meta name="json_note" content="{{route('json_note',compact('mypage_master','note'))}}">


    <meta name="update_note" content="{{route('update_note',$note)}}">
    <meta name="ajax_store_textbox" content="{{route('ajax_store_textbox',$note)}}">
    <meta name="ajax_update_textbox" content="{{route('ajax_update_textbox',$note)}}">
    <meta name="ajax_destroy_textbox" content="{{route('ajax_destroy_textbox',$note)}}">

    <!-- param -->
    <meta name="mypage_master_id" content="{{$mypage_master->id}}">


    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">

    <!-- note_editer.css -->
    <link rel="stylesheet" href="{{asset('css/note_editer.css')}}">

@endsection




@section('script')
    <!-- Vue.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>

    {{-- <script src="{{asset('js/edit_note_vuejs.js')}}"></script> --}}
    @include('note_editer.edit_note_vuejs')

@endsection




@section('title')
    {{!$note? 'ノートの新規作成': '"'.$note->title.'"の編集'}}
@endsection


@section('main.breadcrumb')
    {{ Breadcrumbs::render('edit_note',$mypage_master,$note) }}
@endsection








@section('main.side_container')

    <!-- titileboxの編集 同期処理 -->
    @include('note_editer.form.titlebox')


    <!-- textboxの編集 -->
    @include('note_editer.form.textbox')


    <!-- マイページへ戻るボタン -->
    <div class="input_group_container d-grid gap-2 mb-5">
        <a  href="{{route('mypage_top',$mypage_master)}}"
            class="btn btn-outline-secondary">マイページ
        </a>
    </div>
@endsection







@section('main.top_container')

    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-2">
        <p class="me-2 d-inline bg-primary border border-primary border-5" style="border-radius:.5em;"></p>
        {{!$note? 'ノートの新規作成': '"'.$note->title.'"の編集'}}
    </h2>

    <!-- comment -->
    <h4 class="mb-2 text-secondary">
        <p v-if="!inputMode">
            編集するテキストボックスをプレビュー枠内より選択してください。
        </p>
        <p v-if="inputMode">
            編集の内容をエディターに入力して下さい。
        </p>
    </h4>

    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>

@endsection




@section('main.center_container')

    <!-- ノート表示域 -->
    <ul :class="'preview_note_container display_note_container_'+note.color"> <!-- (クラスからページカラーを指定できる) -->

        <li v-for="(textbox,index) in textboxes">

            <!-- タイトル -->
            <div class="d-flex justify-content-between align-items-end mb-2"
            v-if=" index===0 "
            >

                <!-- タイトルボックスの選択 -->
                <div class="title_box select_textbox"
                v-if=" textbox.mode==='select_textbox' "
                @click="editTitlebox"
                >
                    @include('note_editer.preview.titlebox')
                </div>


                <!-- 編集中タイトルボックス(新規作成) -->
                <div class="title_box editing_textbox" style="cursor:default"
                v-if="inputMode==='create_titlebox'"
                >
                    <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>

                     @include('note_editer.preview.titlebox')
                </div>


                <!-- 編集中タイトルボックス(更新) -->
                <div class="title_box editing_textbox"
                v-if=" (textbox.mode==='editing_textbox') && (inputMode!=='create_titlebox') "
                @click="selectTextbox()"
                >
                    <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>

                     @include('note_editer.preview.titlebox')
                </div>


                <!-- 待機中タイトルボックス -->
                <div class="title_box inoperable_textbox"
                v-if=" textbox.mode==='inoperable_textbox' "
                @click="editTitlebox(textbox,index)"
                >
                     @include('note_editer.preview.titlebox')
            </div>



                <!-- ボタンコンテナ -->
                <div class="btns_container"
                >
                    <button class=" btn btn-primary d-block"
                    @click="createTextbox(index)"
                    v-if="inputMode!=='create_titlebox'"
                    >
                        <i class="bi bi-plus-square-fill d-none d-md-inline"></i>
                        <span>挿入</span>
                    </button>
                </div>


            </div>




            <!-- テキストボックス -->
            <div class="d-flex justify-content-between align-items-end mb-2"
            v-if=" index!==0 "
            >


                <!-- テキストボックスの選択 -->
                <div class="select_textbox"
                v-if=" textbox.mode==='select_textbox' "
                @click="editTextbox(textbox,index)"
                >
                    @include('note_editer.preview.textbox_cases')
                </div>


                <!-- 編集中テキストボックス -->
                <div class="editing_textbox"
                v-if=" textbox.mode==='editing_textbox' "
                @click="selectTextbox()"
                >
                    <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>

                    @include('note_editer.preview.textbox_cases')
                </div>


                <!-- 待機中テキストボックス -->
                <div class="inoperable_textbox"
                v-if=" textbox.mode==='inoperable_textbox' "
                @click="editTextbox(textbox,index)"
                >

                    @include('note_editer.preview.textbox_cases')

                </div>


                <!-- ボタンコンテナ -->
                <div class="btns_container"
                >
                    <button class="btn btn-outline-primary d-block mb-3"
                    @click="deleteTextbox(textbox,index)"
                    v-if="(textbox.mode==='editing_textbox')&&(inputMode!=='create_textbox')"
                    >
                        <i class="bi bi-trash d-none d-md-inline"></i>
                        <span>削除</span>
                    </button>

                    <button class=" btn btn-primary d-block"
                    @click="createTextbox(index)"
                    v-if="!( (textbox.mode==='editing_textbox')&&(inputMode==='create_textbox') )"
                    >
                        <i class="bi bi-plus-square-fill d-none d-md-inline"></i>
                        <span>挿入</span>
                    </button>


                </div>


            </div>



        </li>




    </ul>

@endsection
