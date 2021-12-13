

@extends('layouts.base')




@section('style')
    <!-- note.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/note.css')}}">
    {{-- <!-- edit_form.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/edit_form.css')}}">

    <style>
    .title_box .title{
        min-height: 1.4em;
        line-height: 1.4em;
        font-size: 3em;
        font-weight: bold;
        margin: 0;
    }
    </style> --}}
    <link rel="stylesheet" href="{{asset('css/note_editer.css')}}">



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

    <script src="{{asset('js/edit_note_title_form.js')}}"></script>

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

    @include('note_editer.create_note_side')

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
    <div class="preview_note_container display_note_container_" id="previewContainer">




        <div class="editing_textbox">

            <p class="w-100 text-center" style="color:red;">・・・編集中・・・</p>



            <div class="title_box">
                <small class="d-flex">

                    <!-- 公開設定 (マイページ管理者ログイン時以外は非表示)-->
                    @if ($note && $note->chake_publishing)
                    <span class="note_master_only badge rounded-pill bg-success me-3" style="height:2em">公開中</span>
                    @else
                    <span class="note_master_only badge rounded-pill bg-danger  me-3" style="height:2em">非公開</span>
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
