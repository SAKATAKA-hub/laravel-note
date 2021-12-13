

@extends('layouts.base')




@section('style')

    <!-- token -->
    <meta name="token" content="{{ csrf_token() }}">

    <!-- route -->
    <meta name="json_note" content="{{route('json_note',$note)}}">

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
    @include('note_editer.edit_note_vuejs')

@endsection




@section('title','ノート編集ページ')




@section('main.breadcrumb')

    {{ Breadcrumbs::render('edit_textbox',$mypage_master,$note) }}

@endsection








@section('main.side_container')

    <!-- titileboxの編集 -->
    <div class="fw-bold text-primary"
     v-if="inputMode==='edit_titlebox'"
    >
        タイトルボックスの編集
    </div>

    <div class="card p-2 pt-3 pb-3 mb-5"
     v-if="inputMode==='edit_titlebox'"
    >
        {{-- タイトル --}}
        <div class="form_group mb-3">
            <label class="fw-bold" for="inputNoteTitle">タイトル</label>

            <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle" required
             v-model="editingTextbox.title"
            >
        </div>


        {{-- テーマカラー --}}
        <div class="form_group mb-3">
            <!-- field_label -->
            <label class="fw-bold" for="inputNoteColor">テーマカラー</label>

            <!-- field_input -->
            <select class="form-control" name="color" id="inputNoteColor" required
             v-model="editingTextbox.color"
            >
                <option v-for="color in selects.colors" :value="color.value">
                    @{{color.text}}
                </option>

            </select>
        </div>


        {{-- タグ --}}
        <div class="form_group mb-3">
            <label class="fw-bold">タグ</label>

            <!-- 登録済みタグの選択 -->
            <div class="form-check" v-for="(tag,index) in selects.tags">
                <input class="form-check-input" type="checkbox" :value="tag.text" :id="'tags'+index"
                 v-model="editingTextbox.tags_array"
                >
                <label class="form-check-label" :for="'tags'+index">@{{tag.text}}</label>
            </div>
            <!-- 新しいタグの入力 -->
            <input  class="form-control" type="text" name="tags[]"placeholder="新しいタグの追加 "
             v-model="newTagsString"
            >
        </div>


        {{-- 公開設定 --}}
        <div class="form_group mb-3">
            <label class="fw-bold">公開設定</label>

            <!-- 公開切換え -->
            <div class="form-check form-switch fs-5 mt-2">
                <input class="form-check-input" type="checkbox" id="inputPublishing"
                 v-model="editingTextbox.chake_publishing"
                >

                <label v-if="editingTextbox.chake_publishing"
                 class="form-check-label fs-5 fw-bold text-primary" for="inputPublishing"
                >公開</label>
                <label v-if="!editingTextbox.chake_publishing"
                 class="form-check-label fs-5 fw-bold text-secondary" for="inputPublishing"
                >非公開</label>
            </div>

            <!-- 公開日の予約 -->
            <div v-if="editingTextbox.chake_publishing" class="mt-2">
                <label class="text-secondary" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
                <input class="form-control text-secondary" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
                 min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}" readonly
                 v-model="inputReleaseDatetime"
                >
            </div>
            <div v-if="!editingTextbox.chake_publishing" class="mt-2">
                <label class="" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
                <input class="" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
                 min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}"
                 v-model="inputReleaseDatetime"
                >
            </div>

        </div>


        {{-- ボタン --}}
        <div class="form_group d-grid gap-2 mt-3 mb-3">
            <button type="button" class="btn btn-primary btn-lg"
            @click="saveTitlebox()"
            >ノートの基本情報を保存</button>
        </div>
        <div class="form_group d-grid gap-2">
            <button class="btn btn-outline-secondary"
            @click="selectTextbox()"
            >編集前に戻る</button>
        </div>

    </div>





    <!-- textboxの編集 -->
    <div class="fw-bold text-primary"
    v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
    >
        テキストボックスの編集
    </div>
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
            <!-- create -->
            <form action="{{ route('ajax_store_textbox',$note) }}" method="POST" enctype="multipart/form-data"
            v-if=" inputMode==='create_textbox' "
            >
                @csrf
                <input type="hidden" name="order" value="" id="order">
                <input type="hidden" name="case_name" value="">
                <input type="hidden" name="old_image" value="">
                <input type="hidden" name="group" value="">



                <div class="form_group mb-3">
                    <label class="form-label" for="fileImage">挿入する画像を選択してください。<br>(100Kb以内,jpeg・pngファイルのみ)</label>
                    <input type="file" name="image" class="form-control" id="imageFile" required
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
                    <button type="submit" class="btn btn-primary btn-lg"
                    @click="saveImageTextbox()"
                    >テキストボックスの挿入</button>

                </div>

            </form>


            <!-- update -->
            <form action="{{ route('ajax_update_textbox',$note) }}" method="POST" enctype="multipart/form-data"
            v-if=" inputMode==='edit_textbox' "
            >
                @csrf
                @method('PATCH')
                <input type="hidden" name="id" value="">
                <input type="hidden" name="order" value="">
                <input type="hidden" name="case_name" value="">
                <input type="hidden" name="old_image" value="1"> <!-- 保存済み画像のパス -->
                <input type="hidden" name="group" value="">


                <div class="form_group mb-3">
                    <label class="form-label" for="fileImage">挿入する画像を選択してください。<br>(100Kb以内,jpeg・pngファイルのみ)</label>
                    <input type="file" name="image" class="form-control" id="imageFile"
                    @change="changeImageFile()"
                    ><!-- 画像編集の時は、入力必須ではない -->
                    <p style="color:red;">@{{error.imageFile}}</p>
                </div>


                <div class="form_group mb-3">
                    <label class="fw-bold" for="inputImageSubValue">画像タイトルを入力してください。</label>
                    <input type="text" name="sub_value" class="form-control" placeholder="画像のタイトル"  required
                    v-model="editingTextbox.sub_value"
                    >
                </div>


                <div class="form_group d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg"
                    @click="saveImageTextbox()"
                    >編集内容を保存</button>
                </div>

            </form>


            {{-- <div class="form_group mb-3">
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
            </div> --}}
        </div>


        <!-- backButton -->
        <div class="form_group d-grid gap-2">
            <button class="btn btn-outline-secondary"
            v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox')"
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

                <!-- タイトルボックスの選択 -->
                <div class="title_box select_textbox"
                v-if=" textbox.mode==='select_textbox' "
                @click="editTitlebox(textbox,index)"
                >
                    @include('note_editer.titlebox')
                </div>


                <!-- 編集中タイトルボックス -->
                <div class="title_box editing_textbox"
                v-if=" textbox.mode==='editing_textbox' "
                @click="selectTextbox()"
                >
                    <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>

                    @include('note_editer.titlebox')
                </div>


                <!-- 待機中タイトルボックス -->
                <div class="title_box inoperable_textbox"
                v-if=" textbox.mode==='inoperable_textbox' "
                @click="editTitlebox(textbox,index)"
                >
                    @include('note_editer.titlebox')
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

                    @include('note_editer.textbox_cases')
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
