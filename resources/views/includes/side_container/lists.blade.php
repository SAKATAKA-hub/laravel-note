<div class="mb-5">
    <h5><i class="bi bi-file-earmark-person"></i> 筆者プロフィール</h5>
    <div class="plofile">

        <img class="writer_image" src="{{ asset('storage/'.$mypage_master->image) }}" alt="">
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


<!-- 新着投稿 -->
@if ( isset($seach_heading) or isset($note)) <!-- (ルート'note','mypage_seach'のとき、新着情報一覧を表示) -->
<div class="mb-5">
    <h5><i class="bi bi-file-earmark-text"></i> 新着投稿</h5>
    <ul class="list-group">

        @forelse ($side_lists['new_notes'] as $new_note)
            <li  class="list-group-item p-0">
                <a href="{{route( 'note',compact('mypage_master')+['note'=>$new_note] )}}" class="w-100 p-3 pt-2 pb-2 d-flex justify-content-between align-items-center">
                    {{$new_note->title}}
                    <small class="text-secondary">{{substr($new_note->created_at,0,10)}}</small>
                </a>
            </li>
            @if ($loop->last)
                <li class="list-group-item text-end p-0">
                    <a class="w-100 p-3 pt-2 pb-2 text-primary d-block" href="{{route('mypage_top',$mypage_master)}}">全て表示</a>
                </li>
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


<!-- 投稿月 -->
<div class="mb-5">
    <h5><i class="bi bi-calendar"></i> 投稿月</h5>
    <ul class="list-group">
        @forelse ($side_lists['months'] as $list_item)
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
        @empty
            <li  class="list-group-item text-center">投稿はありません。</li>
        @endforelse
    </ul>
</div>
