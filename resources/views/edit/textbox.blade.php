

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








@section('title')

    @if ( !isset($edit_textbox) )
        テキストボックスの新規挿入
    @else
        テキストボックスの編集
    @endif

@endsection






@section('main.breadcrumb')

    @if ( !isset($edit_textbox) ) <!-- ( create ) -->
        {{ Breadcrumbs::render('create_textbox', $mypage_master,$note) }}
    @else <!-- ( edit ) -->
        {{ Breadcrumbs::render('edit_textbox', $mypage_master,$note) }}
    @endif

@endsection








@section('main.side_container')

    @include('includes.side_container.edit.textbox')

    @include('includes.side_container.edit.back_buttons')

@endsection








@section('main.top_container')

    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        @if ( !isset($edit_textbox) )
            テキストボックスの新規挿入
        @else
            テキストボックスの編集
        @endif
    </h2>


    <h5 class="preview_note_tag"><i class="bi bi-eye"></i>プレビュー</h5>

@endsection




@section('main.center_container')


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
