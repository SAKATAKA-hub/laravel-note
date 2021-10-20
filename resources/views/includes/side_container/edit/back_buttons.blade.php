<div class="input_group_container d-grid gap-4 mb-5">

    @if (isset($order)) <!-- テキストボックスの新規挿入・編集、ノートの編集時のみ表示する -->
        <a href="{{route('edit_note',$note)}}" class="btn btn-outline-secondary">
            別のテキストボックスを編集する
        </a>
    @endif

    <a href="{{route('mypage_top',$mypage_master)}}" class="btn btn-outline-secondary">
        マイページに戻る
    </a>

</div>
