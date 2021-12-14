<div v-if=" textbox.mode!=='editing_textbox' ">
    <!-- 編集中以外　textbox変数のデータを受取り -->

    <div :class="textbox.case_name" v-if="textbox.group ==='heading'" >
        <!-- heading -->
        <p class="mainValue" v-html="textbox.replace_main_value"></p>
    </div>


    <div :class="textbox.case_name" v-if="textbox.group ==='text'" >
        <!-- text -->
        <p class="mainValue" v-html="textbox.replace_main_value"></p>
    </div>


    <div :class="textbox.case_name" v-if="textbox.group ==='link'" >
        <!-- link -->
        <a :href="textbox.main_value" >@{{textbox.sub_value}}</a>
    </div>


    <div :class="textbox.case_name" v-if="textbox.group ==='image'" >
        <!-- image -->
        <img :src="textbox.image_url" :alt="textbox.sub_value">
        <p class="subValue title">@{{textbox.sub_value}}</p>
    </div>
</div>








<div v-if=" textbox.mode==='editing_textbox' ">
    <!-- 編集中　editingTextbox変数のデータを受取り -->

    <div :class="editingTextbox.case_name" v-if="editingTextbox.group ==='heading'" >
        <!-- heading -->
        <p class="mainValue" v-html="editingTextbox.replace_main_value"></p>
    </div>


    <div :class="editingTextbox.case_name" v-if="editingTextbox.group ==='text'" >
        <!-- text -->
        <p class="mainValue" v-html="editingTextbox.replace_main_value"></p>
    </div>


    <div :class="editingTextbox.case_name" v-if="editingTextbox.group ==='link'" >
        <!-- link -->
        <a :href="editingTextbox.main_value" >@{{editingTextbox.sub_value}}</a>
    </div>


    <div :class="editingTextbox.case_name" v-if="editingTextbox.group ==='image'" >
        <!-- image -->
        <img :src="editingTextbox.image_url" :alt="editingTextbox.sub_value" id="imageFilePreview">
        <p class="subValue title">@{{editingTextbox.sub_value}}</p>
    </div>
</div>
