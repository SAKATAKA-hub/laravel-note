<div class="input_group_container mb-5">

    <div class="fw-bold text-primary">基本設定の入力</div>

    <form action="{{route('post_note',$mypage_master)}}" method="POST" class="card p-2 pt-3 pb-3 mb-5">
        @csrf

        {{-- マイページ管理者 --}}
        <input type="hidden" name="mypage_master_id" value="{{$mypage_master->id}}">


        {{-- タイトル --}}
        <div class="form_group mb-4">
            <!-- field_label -->
            <label class="fw-bold" for="inputNoteTitle">タイトル</label>

            <!-- field_input -->
            <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle" required>

            <!-- error_text -->
            <p class="mt-2" style="color:red;"></p>
        </div>


        {{-- テーマカラー --}}
        <div class="form_group mb-4">
            <!-- field_label -->
            <label class="fw-bold" for="inputNoteColor">テーマカラー</label>

            <!-- field_input -->
            <select class="form-control" name="color" id="inputNoteColor" required>
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
        {{-- <div class="form_group mb-4">
            <label class="fw-bold">公開設定</label>


            <div class="form-check form-switch fs-5 mt-2">
                <input class="form-check-input" type="checkbox" name="publishing" id="inputPublishing"
                {{ ($note && $note->chake_publishing)? 'checked': ''}}>


                @if($note && $note->chake_publishing) <!-- 公開 -->

                    <label class="form-check-label fs-5 fw-bold text-primary" for="inputPublishing">公開</label>
                @else <!-- 非公開 -->
                    <label class="form-check-label fs-5 fw-bold text-secondary" for="inputPublishing">非公開</label>
                @endif
            </div>



            <!-- 公開日の予約 -->
            <div class="mt-2">


                @if ($note && $note->chake_publishing) <!-- 公開 -->

                    <label class="text-secondary" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
                    <input class="form-control text-secondary" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
                    min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}" readonly>

                @else<!-- 非公開 -->

                    <label class="" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
                    <input class="form-control" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
                    min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}"
                    value="{{$note? $note->release_datetime_value: ''}}">

                @endif

            </div>

        </div> --}}




        <div class="form_group d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg">基本設定の保存</button>
        </div>

    </form>


</div>
