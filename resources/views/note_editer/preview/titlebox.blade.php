<div v-if=" textbox.mode!=='editing_textbox' ">
    <!-- 編集中以外　note変数のデータを受取り -->
    <small class="d-flex">
        <span class="badge rounded-pill bg-success me-3" style="height:2em"
        v-if="note.chake_publishing">公開中</span>
        <span class="badge rounded-pill bg-danger  me-3" style="height:2em"
        v-if="!note.chake_publishing">非公開</span>

        <div>@{{note.time_text}}</div>
    </small>

    <h3 class="title">@{{note.title}}</h3>

    <small class="d-flex">
        <i class="bi bi-tag-fill me-2"></i>
        <span  v-for="tag in note.tags_array">
            @{{tag}}　
        </span>
    </small>
</div>





<div v-if=" textbox.mode==='editing_textbox' ">
    <!-- 編集中　editingTextbox変数のデータを受取り -->
    <small class="d-flex">
        <span class="badge rounded-pill bg-success me-3" style="height:2em"
        v-if="editingTextbox.chake_publishing">公開中</span>
        <span class="badge rounded-pill bg-danger  me-3" style="height:2em"
        v-if="!editingTextbox.chake_publishing">非公開</span>

        <div>@{{editingTextbox.time_text}}</div>
    </small>

    <h3 class="title">@{{editingTextbox.title}}</h3>

    <small class="d-flex">
        <i class="bi bi-tag-fill me-2"></i>
        <span  v-for="tag in editingTextbox.tags_array">
            @{{tag+'　'}}
        </span>
        <span> @{{newTagsString}}</span>
    </small>
</div>
