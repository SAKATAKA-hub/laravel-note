<script>
    (function(){
        'use strict';
        // token
        const token = document.querySelector('meta[name="token"]').content;
        // route
        const jsonNote = document.querySelector('meta[name="json_note"]').content;
        // param
        const mypageMasterId = document.querySelector('meta[name="mypage_master_id"]').content;




        var app = new Vue({


            el: '#app',

            //------------------------
            // data
            //------------------------
            data: {


                //<-- 入力モード -->
                // 'create_textbox','edit_textbox',''
                inputMode: '',

                //<-- ノート基本情報 -->
                note:
                {
                    title: '',
                    tags:'',
                    created_at: '',
                    color:'',
                },

                //<-- テキストボックス -->
                //* params mode: テキストボックスの表示モード'select_textbox','editing_textbox','inoperable_textbox'　
                textboxes: [],


                //<-- 編集中テキストボックス -->
                // 編集中テキストボックスのインデックス番号
                editingIndex : 0,

                // 編集中テキストボックス
                editingTextbox : [],




                //<-- セレクトボックスの選択要素-->
                selects :{
                    colors : '',
                    tags : '',
                    textbox_cases : '',
                },



                //<-- バリデーションのエラーメッセージ -->
                error:{
                    imageFile:''
                },

            },


            //------------------------
            // mounted
            //------------------------
            mounted:function(){

                // editingTextboxの初期化
                this.editingTextbox = this.retarnResetEditingTextbox();

                // 非同期通信
                fetch(jsonNote, {
                    method: 'POST',
                    body: new URLSearchParams({
                        _token: token,
                        mypage_master_id: mypageMasterId,
                    }),
                })
                .then(response => response.json())
                .then(json => {

                    this.note = json.note ||[]; //noteデータ
                    this.textboxes = json.textboxes ||[]; //textboxデータ
                    this.selects = json.selects ||[]; //セレクト要素

                    console.log(json);
                });


            },


            //------------------------
            // methods
            //------------------------
            methods:{

                /**
                 * 新規挿入フォームの表示(createTextbox(index))
                 *
                 */
                 createTextbox:function(index){

                    // 新規テキストボックスの削除
                    if(this.inputMode === 'create_textbox'){
                        this.textboxes.splice(this.editingIndex, 1);
                        index = index > this.editingIndex? index-1: index;
                    }

                    // editingTextboxの初期化
                    this.editingTextbox = this.retarnResetEditingTextbox();

                    // textboxの表示変更
                    this.textboxes.forEach(thisTextbox => {

                        thisTextbox.mode = 'inoperable_textbox';

                    });


                    // 新規テキストボックスの挿入
                    this.editingTextbox = this.retarnResetEditingTextbox();
                    this.textboxes.splice(index+1, 0, this.editingTextbox);

                    // 編集中テキストボックスデータの保存
                    this.editingIndex = index+1;


                    // エディターの表示変更
                    this.inputMode = 'create_textbox';
                },



                /**
                 * 編集フォームの表示(editTextbox(textbox,index))
                 *
                 */
                 editTextbox:function(textbox,index){

                    // 新規テキストボックスの削除
                    if(this.inputMode === 'create_textbox'){
                        this.textboxes.splice(this.editingIndex, 1);
                    }

                    // textboxの表示変更
                    this.textboxes.forEach( (thisTextbox,i) => {

                        thisTextbox.mode = index === i? 'editing_textbox': 'inoperable_textbox';

                    });


                    // 編集中テキストボックスデータの保存
                    this.editingIndex = index;
                    this.editingTextbox = Object.assign({}, textbox);
                    console.log('editingTextbox:'+this.editingTextbox);


                    // エディターの表示変更
                    this.inputMode = 'edit_textbox';
                },




                /**
                 * 編集中テキストボックスから戻る(selectTextbox())
                 *
                 */
                 selectTextbox:function(){

                    // textboxの表示変更
                    this.textboxes.forEach(textbox => {

                        textbox.mode = 'select_textbox';

                    });

                    // 新規テキストボックスの削除
                    if(this.inputMode === 'create_textbox'){
                        this.textboxes.splice(this.editingIndex, 1);
                    }

                    // editingTextboxの初期化
                    this.editingTextbox = this.retarnResetEditingTextbox();

                    // エディターの表示変更
                    this.inputMode = '';
                },




                /**
                 * 編集中テキストボックスの保存(saveTextbox())
                 *
                 */
                 saveTextbox:function(){

                    //DBへ保存
                    switch (this.inputMode) {

                        case 'create_textbox':
                            console.log('this.inputMode:"create_textbox"');
                            break;

                        case 'edit_textbox':
                            console.log('this.inputMode:"edit_textbox"');
                            break;

                        default:
                        break;
                    }

                    // 編集内容をtextboesデータ配列に保存
                    this.textboxes[this.editingIndex] = Object.assign({}, this.editingTextbox);

                    // textboxの表示変更
                    this.textboxes.forEach(textbox => {

                        textbox.mode = 'select_textbox';

                    });

                    // editingTextboxの初期化
                    this.editingTextbox = this.retarnResetEditingTextbox();

                    // エディターの表示変更
                    this.inputMode = '';
                },





                /**
                 * テキストボックスの削除(deleteTextbox())
                 *
                 */
                 deleteTextbox:function(textbox,index){
                    if( window.confirm('選択中のテキストボックスを削除しますか？') ){

                        this.textboxes.splice(this.editingIndex, 1);

                    }
                },



                /**
                 * 編集中テキストボックスの初期値を返す(retarnResetEditingTextbox)
                 *
                 */
                 retarnResetEditingTextbox:function(){

                    return {
                        mode: 'editing_textbox',
                        main_value : '',
                        replace_main_value : '',
                        sub_value : '',
                        image_url : '',
                        group : '',
                        case_name : '',

                        id : '',
                        note_id : '',
                        textbox_case_id : '',
                    };

                },





                /**
                 * テキストボックスの種類選択の変更(changeInputTextboxCase())
                 *
                 */
                 changeInputTextboxCase:function(){

                    // テキストボックスの種類名 (editingTextboxCaseName)
                    const editingTextboxCaseName = this.editingTextbox.case_name;

                    // テキストボックスのグループ名 (editingTextboxCaseGroup)
                    let editingTextboxCaseGroup = '';
                    this.selects.textbox_cases.forEach(textbox_case => {
                        if(textbox_case.value === editingTextboxCaseName)
                        {
                            editingTextboxCaseGroup = textbox_case.group;
                        }
                    });


                    // データのリセット(テキストボックスのグループが変更するとき)
                    if(this.editingTextbox.group !== editingTextboxCaseGroup){

                        this.editingTextbox = this.retarnResetEditingTextbox();
                        this.editingTextbox.case_name = editingTextboxCaseName;
                    }


                    // テキストボックスのグループ名の更新
                    this.editingTextbox.group =　editingTextboxCaseGroup;

                    console.log(this.editingTextbox.group);


                },




                /**
                 * Heading要素入力(inputHeadingMainValue())
                 * main_valueの値を表示用(replace_main_value)に置き換える
                 */
                 inputHeadingMainValue: function(){

                    this.editingTextbox.replace_main_value = 'replace';


                    // 改行・<strong>タグの差替え関数
                    function replaceValue(value)
                    {
                        value = value.replace('\n', '<br>');
                        value = value.replace('{'+'{','<strong>');
                        value = value.replace('}'+'}', '</strong>');
                        return value;
                    }


                    // 文字列の差し替え
                    let result = '';
                    let replace_value = replaceValue(this.editingTextbox.main_value);
                    while (replace_value!==result) {
                        replace_value = replaceValue(replace_value);
                        result = replaceValue(replace_value);
                    }
                    this.editingTextbox.replace_main_value = replace_value;
                },




                /**
                 * imageファイル要素入力(inputImageFile())
                 *
                 */
                 inputImageFile: function(){

                    // 保存可能なファイル形式
                    const fileTypesArray = ['jpeg','png','jpg'];
                    const maxFileSize = '100000';

                    // ファイル情報の取得
                    let inputImageElement = document.getElementById('imageFile');
                    let imageFilePreview = document.getElementById('imageFilePreview');
                    let fileData = inputImageElement.files;
                    let fileType = fileData[0].name.split('.').pop();
                    let fileSize = fileData[0].size;


                    //ファイルのバリデーション
                    if( fileTypesArray.indexOf(fileType)<0 ){

                        this.error.imageFile = 'ファイル形式が違います。'
                        inputImageElement.value = '';

                    }else if(fileSize > maxFileSize){

                        this.error.imageFile = 'ファイルサイズが100kbを超えています。'
                        inputImageElement.value = '';


                    // エラーなしの処理
                    }else{
                        this.error.imageFile = ''

                        // 読込んだ画像ファイルをプレビューに表示
                        var reader = new FileReader();
                        reader.onload = function(){
                            imageFilePreview.src = reader.result;
                        }
                        reader.readAsDataURL(fileData[0]);

                    }



                },

            },
        });




    })();
</script>

