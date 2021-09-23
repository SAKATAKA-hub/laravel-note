
@extends('layouts.base')




@section('style')

    <!-- side_container.css -->
    <link rel="stylesheet" href="{{asset('css/layouts/side_container.css')}}">

    <!-- note_master_only -->
    @if ( Auth::check() && (Auth::user()->id == $mypage_master->id) )
        <style>
            .main_container .note_master_only{ display: block;}
        </style>
    @endif

@endsection




@section('script')


@endsection






@section('title',$title)





@section('main.breadcrumb')

    {{ Breadcrumbs::render('list', $mypage_master) }}

@endsection




@section('main.side_container')

    @include('includes.side_container.lists')

@endsection




@section('main.center_container')

    <!-- page title -->
    <h2 class="pt-2 pb-2 mb-3">
        <p class="me-2 d-inline bg-primary border border-primary border-5" border-5" style="border-radius:.5em;"></p>
        {{$title}}
    </h2>


    <!-- notes list heading -->
    <h5 class="d-flex justify-content-between align-items-center mb-3" style="padding-right:.5em">
        @switch($list_type)
            @case('seach_word')
                <div class="fs-3"><i class="bi bi-search"></i> 検索キーワード　”{{$seach_value}}”を含む投稿</div>
                @break
            <!-- -->
            @case('tag')
                <div class="fs-3"><i class="bi bi-tag-fill"></i> タグ ”{{$seach_value}}”を含む投稿</div>
                @break
            <!-- -->
            @case('month')
                <div class="fs-3"><i class="bi bi-calendar"></i> 投稿月 {{$seach_value}}の投稿</div>
                @break
            <!-- -->
            @default
                <div class="fs-3"><i class="bi bi-file-earmark-text"></i> 新着投稿</div>
            <!-- -->
        @endswitch

        <button class="note_master_only btn btn-lg btn-primary" style="font-size:.8em;"><i class="bi bi-file-earmark-plus"></i> 新規作成</button>
    </h5>



    <!-- notes list -->
    <ul class="list-group mb-3">


        @forelse ($notes as $note)
            <li class="list-group-item w-100 d-md-flex justify-content-between align-items-center row">

                <div class="col-md-8">
                    <small class="text-muted d-flex">

                        @if ($note->publishing)
                        <span class="note_master_only badge rounded-pill bg-success me-3">公開中</span>
                        @else
                        <span class="note_master_only badge rounded-pill bg-danger  me-3">非公開</span>
                        @endif

                        <span>{{$note->created_at}}</span>


                    </small>


                    <div class="">
                        <a href="{{route('show',$note)}}"  class="">
                            <h3 class="text-primary mt-2">{{$note->title}}</h3>
                        </a>
                    </div>


                    <small class="text-muted">
                        <i class="bi bi-tag-fill"></i>

                        @foreach ($note->tags_array as $tag)
                        <a href="">{{$tag}}　</a>
                        @endforeach
                    </small>

                </div>


                <div class="note_master_only col-md-4 text-md-end mt-3 mt-md-0">
                    <button class="btn btn-secondary"><i class="bi bi-eraser-fill"></i> 編集</button>
                    <button class="btn btn-secondary"><i class="bi bi-trash"></i> 削除</button>
                </div>

            </li>
        @empty
            <li class="list-group-item w-100 text-center p-5 fs-4 text-secondary">
                投稿はありません
            </li>
        @endforelse


    </ul>




    <!-- pagenation -->
    {{-- {{ $notes->links('includes.pagination.oliginal') }} --}}


@endsection
