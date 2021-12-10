

@extends('layouts.base')




@section('style')

    <!-- token -->
    <meta name="token" content="{{ csrf_token() }}">

    <!-- route -->
    <meta name="json_note" content="{{route('json_note',$note)}}">
    <meta name="ajax_store_textbox" content="{{route('ajax_store_textbox',$note)}}">
    <meta name="ajax_update_textbox" content="{{route('ajax_update_textbox',$note)}}">
    <meta name="ajax_destroy_textbox" content="{{route('ajax_destroy_textbox',$note)}}">



    <!-- param -->
    <meta name="mypage_master_id" content="{{$mypage_master->id}}">



    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">

    <!-- edit_form.css -->
    {{-- <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}"> --}}

    <!-- note_editer.css -->
    <link rel="stylesheet" href="{{asset('css/note_editer.css')}}">

@endsection




@section('script')
    <!-- Vue.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    @include('note_editer.edit_note_vuejs')

@endsection







{{-- @section('title',$note->title.'"の編集') --}}
@section('title','test ノート編集ページ')




@section('main.breadcrumb')

    {{ Breadcrumbs::render('edit_textbox',$mypage_master,$note) }}

@endsection








@section('main.side_container')

    <form action="{{route('ajax_destroy_textbox',$note)}}" method="POST">
        @csrf
        @method('DELETE')
        <button>test</button>
    </form>

    <div class="fw-bold text-primary"
    v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
    >テキストボックスの編集</div>
    <div class="card p-2 pt-3 pb-3 mb-5"
    v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
    >

        <!-- inputTextboxCase -->
        <div class="form_group mb-3">
            <label class="fw-bold text-primary" for="inputTextBoxCase">テキストボックスの種類選択</label>
            <select class="form-control text-primary fw-bold" name="age_group" id="inputTextBoxCase"
            v-model="editingTextbox.case_name"
            @change="changeTextboxCase()"
            >
                <option value="">-- 選択 --</option>

                <option v-for="textbox_case in selects.textbox_cases"
                :value="textbox_case.value"
                >
                    @{{textbox_case.text}}
                </option>

            </select>
        </div>




        <!-- inputHeading -->
        <div class="input_group_container d-grid gap-2 mb-3"
        v-if=" editingTextbox.group==='heading' "
        >

            <div class="form_group mb-3">

                <label class="fw-bold" for="inputHeadingMainValue">見出しを入力して下さい。 <br>(100文字以内)</label>

                <input type="text" name="main_value" class="form-control" required
                v-model="editingTextbox.main_value"
                @change="changeReplaceMainValue()"
                >
                <p style="color:red;">@{{error.strMax}}</p>


                <p>※重要な言葉は @{{ '{'+'{' }}  @{{ '}'+'}' }} (半角記号) で囲むことで強調させることができます。</p>

            </div>


            <div class="form_group d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='create_textbox' "
                @click="saveTextbox()"
                >テキストボックスの挿入</button>

                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='edit_textbox' "
                @click="saveTextbox()"
                >編集内容を保存</button>
            </div>

        </div>




        <!-- inputText -->
        <div class="input_group_container d-grid gap-2 mb-3"
        v-if=" editingTextbox.group==='text' "
        >
            <div class="form_group mb-3">
                <label class="fw-bold" for="inputTextMainValue">文章を入力して下さい。</label>

                <textarea name="main_value" class="form-control" style="height:12rem;"
                placeholder="※重要な言葉は  (半角記号) で囲む。" required
                v-model="editingTextbox.main_value"
                @change="changeReplaceMainValue()"
                ></textarea>

                <p>※重要な言葉は @{{ '{'+'{' }}  @{{ '}'+'}' }} (半角記号) で囲むことで強調させることができます。</p>
            </div>

            <div class="form_group d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='create_textbox' "
                @click="saveTextbox()"
                >テキストボックスの挿入</button>

                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='edit_textbox' "
                @click="saveTextbox()"
                >編集内容を保存</button>
            </div>
        </div>




        <!-- inputLink -->
        <div class="input_group_container d-grid gap-2 mb-3"
        v-if=" editingTextbox.group==='link' "
        >
            <div class="form_group mb-3">
                <label class="fw-bold" for="inputLinkMainValue">リンク先URLを入力して下さい。</label>
                <input type="text" name="main_value" class="form-control" placeholder="半角記号英数字" required
                v-model="editingTextbox.main_value"
                >
            </div>

            <div class="form_group mb-3">
                <label class="fw-bold" for="inputLinkSubValue">リンクタイトルを入力してください。</label>
                <input type="text" name="sub_value" class="form-control" placeholder="リンクタイトル"  required
                v-model="editingTextbox.sub_value"
                >
            </div>

            <div class="form_group d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='create_textbox' "
                @click="saveTextbox()"
                >テキストボックスの挿入</button>

                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='edit_textbox' "
                @click="saveTextbox()"
                >編集内容を保存</button>
            </div>
        </div>



        <!-- inputImage -->
        <div class="input_group_container d-grid gap-2 mb-3"
        v-if=" editingTextbox.group==='image' "
        >
            <div class="form_group mb-3">
                <label class="form-label" for="fileImage">挿入する画像を選択してください。<br>(100Kb以内,jpeg・pngファイルのみ)</label>
                <input type="file" name="image" class="form-control" id="imageFile"
                @change="changeImageFile()"
                ><!-- 画像編集の時は、入力必須ではない -->
                <p style="color:red;">@{{error.imageFile}}</p>
            </div>

            <div class="form_group mb-3">
                <label class="fw-bold" for="inputImageSubValue">画像タイトルを入力してください。</label>
                <input type="text" name="sub_value" class="form-control" placeholder="画像のタイトル" required
                v-model="editingTextbox.sub_value"
                >
            </div>

            <div class="form_group d-grid gap-2">
                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='create_textbox' "
                @click="saveTextbox()"
                >テキストボックスの挿入</button>

                <button type="button" class="btn btn-primary btn-lg"
                v-if=" inputMode==='edit_textbox' "
                @click="saveTextbox()"
                >編集内容を保存</button>
            </div>
        </div>


        <!-- backButton -->
        <div class="form_group d-grid gap-2">
            <button class="btn btn-outline-secondary"
            v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
            @click="selectTextbox()"
            >編集前に戻る</button>
        </div>


    </div>


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
        "{{$note->title}}"の編集
    </h2>

    <h4 class="mb-2 text-secondary">編集の操作内容をプレビュー枠内のボタンより選択してください。</h4>

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

                <!-- テキストボックス選択中 -->
                <div :class="'title_box '+ textbox.mode">


                    <small class="d-flex">
                        <span class="badge rounded-pill bg-success me-3" style="height:2em"
                        v-if="note.chake_publishing">公開中</span>
                        <span class="badge rounded-pill bg-danger  me-3" style="height:2em"
                        v-if="!note.chake_publishing">非公開</span>

                        <div>@{{note.time_text}}</div>
                    </small>

                    <h3 class="title">@{{note.title}}</h3>

                    <small class="d-flex">
                        <i class="bi bi-tag-fill me-2"></i>
                        <span  v-for="tag in note.tags_array">
                            @{{tag}}　
                        </span>
                    </small>


                </div>




                <!-- ボタンコンテナ -->
                <div class="btns_container"
                >
                    <button class=" btn btn-primary d-block"
                    @click="createTextbox(index)"
                    v-if="!( (textbox.mode==='editing_textbox')&&(inputMode==='create_textbox') )"
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

                    @include('note_editer.textbox_cases')

                </div>


                <!-- 編集中テキストボックス -->
                <div class="editing_textbox"
                v-if=" textbox.mode==='editing_textbox' "
                @click="selectTextbox()"
                >
                    <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>

                    <div id="editingElement"> <!--#editingElement : 要素内のHTML内容を操作-->

                        @include('note_editer.textbox_cases')

                    </div>
                </div>


                <!-- 待機中テキストボックス -->
                <div class="inoperable_textbox"
                v-if=" textbox.mode==='inoperable_textbox' "
                @click="editTextbox(textbox,index)"
                >

                    @include('note_editer.textbox_cases')

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
