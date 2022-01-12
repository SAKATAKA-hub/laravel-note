<div v-if="(inputMode==='create_titlebox')||(inputMode==='edit_titlebox')"  class="fw-bold text-primary">
   ノート基本情報の入力
</div>

@if (!$note) <!-- ノートの新規作成ルーティング -->
    <form action="{{route('post_note',$mypage_master)}}" method="POST" class="card p-2 pt-3 pb-3 mb-5"
     v-if="(inputMode==='create_titlebox')||(inputMode==='edit_titlebox')"
     @submit="checkTitleForm"
    >
    @csrf

@else <!-- ノートの更新ルーティング -->
    <form action="{{route('update_note',$note)}}" method="POST" class="card p-2 pt-3 pb-3 mb-5"
     v-if="(inputMode==='create_titlebox')||(inputMode==='edit_titlebox')"
     @submit="checkTitleForm"
    >
    @csrf
    @method('PATCH')

@endif
   {{-- <input type="hidden" name="mypage_master_id" value="{{$note? $note->user_id :''}}"> --}}
   <input type="hidden" name="mypage_master_id" value="{{$mypage_master->id}}">

   {{-- タイトル --}}
   <div class="form_group mb-3">
       <label class="fw-bold" for="inputNoteTitle">タイトル</label>

       <input type="text" name="title" class="form-control" placeholder="タイトル" id="inputNoteTitle" required
        v-model="editingTextbox.title"
       >
   </div>


   {{-- テーマカラー --}}
   <div class="form_group mb-3">
       <!-- field_label -->
       <label class="fw-bold" for="inputNoteColor">テーマカラー</label>

       <!-- field_input -->
       <select class="form-control" name="color" id="inputNoteColor" required
        {{-- v-model="editingTextbox.color" --}}
        v-model="note.color"
       >
            <option value="">選択してください</option>
            <option v-for="color in selects.colors" :value="color.value">
                @{{color.text}}
            </option>

       </select>
   </div>


   {{-- タグ --}}
   <div class="form_group mb-3">
       <label class="fw-bold">タグ</label>

       <!-- 登録済みタグの選択 -->
       <div class="form-check" v-for="(tag,index) in selects.tags">
           <input class="form-check-input" type="checkbox" name="tags[]" :value="tag.text" :id="'tags'+index"
            v-model="editingTextbox.tags_array"
           >
           <label class="form-check-label" :for="'tags'+index">@{{tag.text}}</label>
       </div>
       <!-- 新しいタグの入力 -->
       <input  class="form-control" type="text" name="tags[]" placeholder="新しいタグの追加 "
        v-model="newTagsString"
       >
       <!-- エラー表示 -->
       <p style="color:red;">@{{error.tag}}</p>
   </div>


   {{-- 公開設定 --}}
   <div class="form_group mb-3">
       <label class="fw-bold">公開設定</label>

       <!-- 公開切換え -->
       <div class="form-check form-switch fs-5 mt-2">
           <input class="form-check-input" type="checkbox" name="publishing" id="inputPublishing"
            v-model="editingTextbox.chake_publishing"
           >

           <label v-if="editingTextbox.chake_publishing"
            class="form-check-label fs-5 fw-bold text-primary" for="inputPublishing"
           >公開</label>
           <label v-if="!editingTextbox.chake_publishing"
            class="form-check-label fs-5 fw-bold text-secondary" for="inputPublishing"
           >非公開</label>
       </div>

       <!-- 公開日の予約 -->
       <div v-if="editingTextbox.chake_publishing" class="mt-2">
           <label class="text-secondary" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
           <input class="form-control text-secondary" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
            min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}" readonly
            v-model="editingTextbox.release_datetime_value"
           >
       </div>
       <div v-if="!editingTextbox.chake_publishing" class="mt-2">
           <label class="" for="inputReleaseDatetime">公開日を予約する(翌日以降)</label>
           <input class="form-control" type="datetime-local" name="release_datetime" id="inputReleaseDatetime"
            min="{{\Carbon\Carbon::parse('tomorrow')->format('Y-m-d').'T00:00'}}"
            v-model="editingTextbox.release_datetime_value"
           >
       </div>

   </div>


   {{-- ボタン --}}
   <div class="form_group d-grid gap-2 mt-3 mb-3">
        <button v-if="inputMode==='create_titlebox'" type="submit" class="btn btn-primary btn-lg">
           ノートの基本情報の保存
        </button>

        <button v-if="inputMode==='edit_titlebox'" type="submit" class="btn btn-primary btn-lg">
            ノート基本情報の更新
        </button>
   </div>
   <div class="form_group d-grid gap-2">
       <button type="button" class="btn btn-outline-secondary"
        @click="selectTextbox()"
       >編集前に戻る</button>
   </div>

</form>
