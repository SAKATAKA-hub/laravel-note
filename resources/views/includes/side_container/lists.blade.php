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
    <h5><i class="bi bi-search"></i> {{$mypage_master->name}} さんの、ノートのタイトルから検索</h5>
    @php
        $param = ['list_type' => 'seach_title'];
    @endphp
    <form class="d-flex" method="GET" action="{{route( 'seach_list',compact('mypage_master')+$param )}}" >
        <input class="form-control me-2" name="seach_value" type="search" placeholder="タイトル" aria-label="Search" required>
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>


<!-- 新着投稿 -->
@if ( !isset($list_type) or $list_type) <!-- (ルート'show','seach_list'のとき、新着情報を表示) -->
<div class="mb-5">
    <h5><i class="bi bi-file-earmark-text"></i> 新着投稿</h5>
    <ul class="list-group">

        @forelse ($side_lists['new_notes'] as $new_note)
            <li  class="list-group-item">
                <a class="d-flex justify-content-between align-items-center" href="{{route('show',$new_note)}}">
                    {{$new_note->title}}
                    <small class="text-secondary">{{substr($new_note->created_at,0,10)}}</small>
                </a>
            </li>
            @if ($loop->last)
                <li class="list-group-item text-end">
                    <a class="text-primary d-block" href="{{route('list',$mypage_master)}}">全て表示</a>
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
            @php
                $param = [
                    'list_type' => 'tag',
                    'seach_value' => $list_item['value'],
                ];
            @endphp

            <li  class="list-group-item">
                <a class="d-flex justify-content-between align-items-center"
                href="{{route( 'seach_list',compact('mypage_master')+$param )}}">
                    {{$list_item['text']}}
                    <span class="badge bg-primary rounded-pill">{{$list_item->count}}</span>
                </a>
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
            @php
                $param = [
                    'list_type' => 'month',
                    'seach_value' => $list_item['value'],
                ];
            @endphp

            <li  class="list-group-item">
                <a class="d-flex justify-content-between align-items-center"
                href="{{route( 'seach_list',compact('mypage_master')+$param )}}">
                    {{$list_item['text']}}
                    <span class="badge bg-primary rounded-pill">{{$list_item['count']}}</span>
                </a>
            </li>
        @empty
            <li  class="list-group-item text-center">投稿はありません。</li>
        @endforelse
    </ul>
</div>
