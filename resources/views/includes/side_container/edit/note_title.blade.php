<div class="input_group_container">

    @if (!$note)
    <form action="{{route('store_note_title')}}" method="POST">
    @else
    <form action="{{route('update_note_title',$note)}}" method="POST">
    @endif
        @csrf
        <input type="hidden" name="mypage_master_id" value="{{$mypage_master->id}}">

        <div class="form_group mb-4">
            <label class="fw-bold" for="inputNoteTitle">タイト</label>
            <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle"
            value="{{!$note? '': $note->title}}" required>
            <p style="color:red;margin-top:.5em;"></p>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold" for="inputNoteColor">テーマカラー</label>
            <select class="form-control" name="color" id="inputNoteColor" required>
                @foreach ($selects['colors'] as $select)
                    <option value="{{$select->value}}" {{$select->selected? 'selected':''}}>{{$select->text}}</option>
                @endforeach
            </select>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold">タグ</label>

                @foreach ($selects['tags'] as $i => $select)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{$select->text}}"
                        id="{{'inputNoteTag'.$i}}" {{$i==0? 'required': ''}} {{$select->checked? 'checked':''}}>
                        <label class="form-check-label" for="{{'inputNoteTag'.$i}}">
                            {{$select->text}}
                        </label>
                    </div>
                @endforeach

                <input  class="form-control" type="text" name="tags[]"placeholder="新しいタグの追加 " id="newTags">

            <p style="color:red;height:1em;"></p>
        </div>


        <div class="form_group mb-4">
            <label class="fw-bold">公開設定</label>
            <div class="form-check form-switch fs-5 mt-2">
                <input class="form-check-input" type="checkbox" name="publishing" id="inputPublishing"
                {{$note && !$note->publishing? '': 'checked'}}>

                @if ($note && !$note->publishing)
                <label class="form-check-label fs-5 fw-bold text-secondary" for="inputPublishing">非公開</label>
                @else
                <label class="form-check-label fs-5 fw-bold text-primary" for="inputPublishing">公開</label>
                @endif
            </div>
        </div>


        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">基本設定の保存</button>
        </div>

    </form>


</div>
