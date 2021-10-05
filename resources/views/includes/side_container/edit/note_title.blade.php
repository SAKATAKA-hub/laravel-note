<div class="input_group_container">

    @if (!$note)
    <form action="{{route('store_note_title',$mypage_master)}}" method="POST">
    @else
    <form action="{{route('update_note_title',$note)}}" method="POST">
        @method('PATCH')
    @endif
        @csrf


        {{-- マイページ管理者 --}}
        <input type="hidden" name="mypage_master_id" value="{{$mypage_master->id}}">


        {{-- タイトル --}}
        <div class="form_group mb-4">
            <!-- field_label -->
            <label class="fw-bold" for="inputNoteTitle">タイト</label>

            <!-- field_input -->
            @if (!$note)
                <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle"
                value="{{ old('title') }}">
            @else
                <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle"
                value="{{ old('title', $note->title) }}">
            @endif

            <!-- error_text -->
            @error('title')
                <p class="mt-2" style="color:red;">{{ $message }}</p>
            @enderror
        </div>


        {{-- テーマカラー --}}
        <div class="form_group mb-4">
            <!-- field_label -->
            <label class="fw-bold" for="inputNoteColor">テーマカラー</label>

            <!-- field_input -->
            <select class="form-control" name="color" id="inputNoteColor">
                <option value="">選択してください</option>

                @foreach ($selects['colors'] as $select)

                    @if ( $errors->all() )
                        <option value="{{$select->value}}"
                            {{old('color') == $select->value? 'selected': ''}}>
                            {{$select->text}}
                        </option>
                    @else
                        <option value="{{$select->value}}"
                            {{$select->selected? 'selected':''}}>
                            {{$select->text}}
                        </option>
                    @endif

                @endforeach
            </select>


            <!-- error_text -->
            @error('color')
                <p class="mt-2" style="color:red;">{{ $message }}</p>
            @enderror
        </div>


        {{-- タグ --}}
        <div class="form_group mb-4">
            <label class="fw-bold">タグ</label>

            <!-- 登録済みタグの選択 -->
            @foreach ($selects['tags'] as $i => $select)

                @if ( old('tags') ) <!-- (エラーがあるとき) -->

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{$select->text}}" id="{{'inputNoteTag'.$i}}"
                        {{ in_array( $select->text, old('tags') )?  'checked': ''}}>
                        <label class="form-check-label" for="{{'inputNoteTag'.$i}}"> {{$select->text}} </label> <!-- (テキスト) -->
                    </div>

                @elseif($errors->all()) <!-- (エラーがあり、チェックボックスになにもチェックがないとき) -->

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{$select->text}}" id="{{'inputNoteTag'.$i}}">
                        <label class="form-check-label" for="{{'inputNoteTag'.$i}}"> {{$select->text}} </label>　<!-- (テキスト) -->
                    </div>

                @else <!-- (エラーがないとき) -->

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tags[]" value="{{$select->text}}" id="{{'inputNoteTag'.$i}}"
                        {{$select->checked? 'checked':''}}>
                        <label class="form-check-label" for="{{'inputNoteTag'.$i}}"> {{$select->text}} </label>　<!-- (テキスト) -->
                    </div>
                @endif

            @endforeach

            <!-- 新しいタグの入力 -->
            @if ( $errors->all() ) <!-- (エラーがあるとき) -->
                <?php $n = count(old('tags'))-1; ?>
                <input  class="form-control" type="text" name="tags[]"placeholder="新しいタグの追加 " id="newTags"
                value="{{$errors->all() ? old('tags')[$n]: ''}}" >
            @else <!-- (エラーがないとき) -->
                <input  class="form-control" type="text" name="tags[]"placeholder="新しいタグの追加 " id="newTags"
                value="" >
            @endif

            <!-- error_text -->
            @error('tags')
                <p class="mt-2" style="color:red;">{{ $message }}</p>
            @enderror
            @error('tags.*')
                <p class="mt-2" style="color:red;">{{ $message }}</p>
            @enderror

        </div>


        {{-- 公開設定 --}}
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
