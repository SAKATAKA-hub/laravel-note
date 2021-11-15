<div class="mb-5">
    <h5><i class="bi bi-file-earmark-person"></i> 筆者プロフィール</h5>
    <div class="plofile">

        <img class="writer_image" src="{{ $mypage_master->image_url }}" alt="">

        <div class="writer_text">
            <p class="text-center"><strong>{{$mypage_master->name}} さん</strong></p>
            <p><strong>{{$mypage_master->comment}}</strong></p>
        </div>

    </div>
</div>



<!-- noteのタイトルで検索 -->
<div class="mb-5">
    <h5><i class="bi bi-search"></i> ノートのタイトルから検索</h5>
    <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
        <input type="hidden" name="list_type" value="seach_title">
        <input class="form-control me-2" name="seach_value" type="search" placeholder="タイトル" aria-label="Search" required>
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>




<!-- 公開中ノート -->
@if ( isset($seach_heading) or isset($note) or ( Auth::check() && (Auth::user()->id == $mypage_master->id) )) <!-- (ルート'note','mypage_seach',またはマイページ管理者ログイン中のとき、新着情報一覧を表示) -->
<div class="mb-5">
    <h5><i class="bi bi-file-earmark-text"></i> 公開中ノート</h5>
    <ul class="list-group">

        @forelse ($side_lists['new_notes'] as $new_note)
            <!-- ノートのリスト -->
            <li  class="list-group-item p-0">
                <a href="{{route( 'note',compact('mypage_master')+['note'=>$new_note] )}}" class="w-100 p-3 pt-2 pb-2 d-block">
                    {{$new_note->title}}
                    <small class="text-secondary d-block">{{$new_note->publication_at_text}}</small>
                </a>
                {{-- d-flex justify-content-between align-items-center --}}
            </li>

            <!-- 全てのノート表示ボタン -->
            @if ($loop->last)

                @if ( Auth::check() && (Auth::user()->id == $mypage_master->id) )
                    <li class="text-end p-0" style="list-style:none;">
                        <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                            <input type="hidden" name="list_type" value="publishing"> <!-- name="list_type" -->
                            <input type="hidden" name="seach_value" value=""> <!--name="seach_value" -->
                            <button class="w-100 p-3 pt-2 pb-2 text-primary d-block text-end" type="submit">
                                公開中ノート一覧の表示
                            </button>
                        </form>
                    </li>
                    <li class="text-end p-0" style="list-style:none;">
                        <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                            <input type="hidden" name="list_type" value="unpublished"> <!-- name="list_type" -->
                            <button class="w-100 p-3 pt-2 pb-2 text-primary d-block text-end" type="submit">
                                未公開ノート一覧の表示
                            </button>
                        </form>
                    </li>
                    <li class="text-end p-0" style="list-style:none;">
                        <a class="w-100 p-3 pt-2 pb-2 text-primary d-block" href="{{route('mypage_top',$mypage_master)}}">
                            非公開を含めたノート一覧の表示
                        </a>
                    </li>
                @else
                <li class="text-end p-0" style="list-style:none;">
                        <a class="w-100 p-3 pt-2 pb-2 text-primary d-block" href="{{route('mypage_top',$mypage_master)}}">
                            全て表示
                        </a>
                    </li>
                @endif

            @endif

        @empty
            <li  class="list-group-item text-center">投稿はありません。</li>

        @endforelse

    </ul>
</div>
@endif

<!-- タグ -->
<div class="mb-5">
    <h5><i class="bi bi-tag-fill"></i> タグ</h5>
    <ul class="list-group">
        @forelse ($side_lists['tags'] as $list_item)
            <li  class="list-group-item p-0">

                <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                    <input type="hidden" name="list_type" value="tag"> <!-- name="list_type" -->
                    <input type="hidden" name="seach_value" value="{{$list_item['value']}}"> <!--name="seach_value" -->
                    <button class="w-100 p-3 pt-2 pb-2 d-flex justify-content-between align-items-center" type="submit">
                        {{$list_item['text']}}
                        <span class="badge bg-primary rounded-pill">{{$list_item->count}}</span>
                    </button>
                </form>

            </li>
        @empty
            <li  class="list-group-item text-center">投稿はありません。</li>
        @endforelse
    </ul>
</div>


<!-- 公開月 -->
<div class="mb-5">
    <h5><i class="bi bi-calendar"></i> 公開月</h5>
    <ul class="list-group">
        @forelse ($side_lists['months'] as $list_item)

            @if ($list_item['text']!=='未公開')
                <li  class="list-group-item p-0">
                    <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                        <input type="hidden" name="list_type" value="month"> <!-- name="list_type" -->
                        <input type="hidden" name="seach_value" value="{{$list_item['value']}}"> <!--name="seach_value" -->
                        <button class="w-100 p-3 pt-2 pb-2 d-flex justify-content-between align-items-center" type="submit">
                            {{$list_item['text']}}
                            <span class="badge bg-primary rounded-pill">{{$list_item['count']}}</span>
                        </button>
                    </form>
                </li>
            @else
                <!-- 未公開リスト -->
                <li  class="list-group-item p-0">
                    <form class="d-flex" method="GET" action="{{route( 'mypage_seach',$mypage_master )}}" >
                        <input type="hidden" name="list_type" value="unpublished"> <!-- name="list_type" -->
                        <button class="w-100 p-3 pt-2 pb-2 d-flex justify-content-between align-items-center" type="submit">
                            {{$list_item['text']}} <!-- 未公開 -->
                            <span class="badge bg-primary rounded-pill">{{$list_item['count']}}</span>
                        </button>
                    </form>
                </li>
            @endif

        @empty

            <li  class="list-group-item text-center">投稿はありません。</li>

        @endforelse
    </ul>
</div>
