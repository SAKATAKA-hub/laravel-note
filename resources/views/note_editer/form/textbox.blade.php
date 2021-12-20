<div class="fw-bold text-primary"
v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
>
    テキストボックスの編集
</div>
<div class="card p-2 pt-3 pb-3 mb-5"
 v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox') "
>

    <!-- inputTextboxCase -->
    <div class="form_group mb-3">
        <label class="fw-bold text-primary" for="inputTextBoxCase">テキストボックスの種類選択</label>
        <select class="form-control text-primary fw-bold" name="age_group" id="inputTextBoxCase"
         v-model="editingTextbox.case_name"
         @change="changeTextboxCase()"
        >
            <option value="">-- 選択 --</option>

            <option v-for="textbox_case in selects.textbox_cases"
             :value="textbox_case.value"
            >
                @{{textbox_case.text}}
            </option>

        </select>
    </div>




    <!-- inputHeading -->
    <div class="input_group_container d-grid gap-2 mb-3"
     v-if=" editingTextbox.group==='heading' "
    >

        <div class="form_group mb-3">

            <label class="fw-bold" for="inputHeadingMainValue">見出しを入力して下さい。 <br>(100文字以内)</label>

            <input type="text" name="main_value" class="form-control" required
             v-model="editingTextbox.main_value_input"
             @change="changeReplaceMainValue()"
            >
            <p style="color:red;">@{{error.main_value_input}}</p>

            <p>※重要な言葉は @{{ '{'+'{' }}  @{{ '}'+'}' }} (半角記号) で囲むことで強調させることができます。</p>

        </div>


        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='create_textbox' "
             @click="saveTextbox()"
            >テキストボックスの挿入</button>

            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='edit_textbox' "
             @click="saveTextbox()"
            >編集内容を保存</button>
        </div>

    </div>




    <!-- inputText -->
    <div class="input_group_container d-grid gap-2 mb-3"
     v-if=" editingTextbox.group==='text' "
    >
        <div class="form_group mb-3">
            <label class="fw-bold" for="inputTextMainValue">文章を入力して下さい。</label>

            <textarea name="main_value" class="form-control" style="height:12rem;"
             placeholder="※重要な言葉は  (半角記号) で囲む。" required
             v-model="editingTextbox.main_value_input"
             @change="changeReplaceMainValue()"
            ></textarea>

            <p>※重要な言葉は @{{ '{'+'{' }}  @{{ '}'+'}' }} (半角記号) で囲むことで強調させることができます。</p>
        </div>

        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='create_textbox' "
             @click="saveTextbox()"
            >テキストボックスの挿入</button>

            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='edit_textbox' "
             @click="saveTextbox()"
            >編集内容を保存</button>
        </div>
    </div>




    <!-- inputLink -->
    <div class="input_group_container d-grid gap-2 mb-3"
     v-if=" editingTextbox.group==='link' "
    >
        <div class="form_group mb-3">
            <label class="fw-bold" for="inputLinkMainValue">リンク先URLを入力して下さい。</label>
            <input type="text" name="main_value" class="form-control" placeholder="半角記号英数字" required
             v-model="editingTextbox.main_value_input"
            >
            <p style="color:red;">@{{error.main_value_input}}</p>
        </div>

        <div class="form_group mb-3">
            <label class="fw-bold" for="inputLinkSubValue">リンクタイトルを入力してください。</label>
            <input type="text" name="sub_value" class="form-control" placeholder="リンクタイトル"  required
             v-model="editingTextbox.sub_value"
            >
            <p style="color:red;">@{{error.sub_value}}</p>
        </div>

        <div class="form_group d-grid gap-2">
            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='create_textbox' "
             @click="saveTextbox()"
            >テキストボックスの挿入</button>

            <button type="button" class="btn btn-primary btn-lg"
             v-if=" inputMode==='edit_textbox' "
             @click="saveTextbox()"
            >編集内容を保存</button>
        </div>
    </div>



    <!-- inputImage -->
    <div class="input_group_container d-grid gap-2 mb-3"
     v-if=" editingTextbox.group==='image' "
    >
        <!-- create -->
        <form action="{{ route('ajax_store_textbox',$note) }}" method="POST" enctype="multipart/form-data"
         v-if="inputMode==='create_textbox'"
         @submit="saveImageTextbox"
        >
            @csrf
            <input type="hidden" name="order" value="" id="order">
            <input type="hidden" name="case_name" value="">
            <input type="hidden" name="old_image" value="">
            <input type="hidden" name="group" value="">



            <div class="form_group mb-3">
                <label class="form-label" for="fileImage">挿入する画像を選択してください。<br>(100Kb以内,jpeg・pngファイルのみ)</label>
                <input type="file" name="image" class="form-control" id="imageFile" required
                 @change="changeImageFile()"
                ><!-- 画像編集の時は、入力必須ではない -->
                <p style="color:red;">@{{error.imageFile}}</p>
            </div>


            <div class="form_group mb-3">
                <label class="fw-bold" for="inputImageSubValue">画像タイトルを入力してください。</label>
                <input type="text" name="sub_value" class="form-control" placeholder="画像のタイトル" required
                 v-model="editingTextbox.sub_value"
                >
            </div>


            <div class="form_group d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">テキストボックスの挿入</button>
            </div>

        </form>


        <!-- update -->
        <form action="{{ route('ajax_update_textbox',$note) }}" method="POST" enctype="multipart/form-data"
         v-if=" inputMode==='edit_textbox' "
         @submit="saveImageTextbox"
        >
            @csrf
            @method('PATCH')
            <input type="hidden" name="id" value="">
            <input type="hidden" name="order" value="">
            <input type="hidden" name="case_name" value="">
            <input type="hidden" name="old_image" value="1"> <!-- 保存済み画像のパス -->
            <input type="hidden" name="group" value="">


            <div class="form_group mb-3">
                <label class="form-label" for="fileImage">挿入する画像を選択してください。<br>(100Kb以内,jpeg・pngファイルのみ)</label>
                <input type="file" name="image" class="form-control" id="imageFile"
                @change="changeImageFile()"
                ><!-- 画像編集の時は、入力必須ではない -->
                <p style="color:red;">@{{error.imageFile}}</p>
            </div>


            <div class="form_group mb-3">
                <label class="fw-bold" for="inputImageSubValue">画像タイトルを入力してください。</label>
                <input type="text" name="sub_value" class="form-control" placeholder="画像のタイトル"  required
                v-model="editingTextbox.sub_value"
                >
            </div>


            <div class="form_group d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">編集内容を保存</button>
            </div>

        </form>
    </div>


    <!-- backButton -->
    <div class="form_group d-grid gap-2">
        <button class="btn btn-outline-secondary"
        v-if=" (inputMode==='create_textbox')||(inputMode==='edit_textbox')"
        @click="selectTextbox()"
        >編集前に戻る</button>
    </div>


</div>
