<script>
    (function(){
        'use strict';
        // token
        const token = document.querySelector('meta[name="token"]').content;

        // route
        const jsonNote = document.querySelector('meta[name="json_note"]').content;
        const ajax_store_textbox = document.querySelector('meta[name="ajax_store_textbox"]').content;
        const ajax_destroy_textbox = document.querySelector('meta[name="ajax_destroy_textbox"]').content;

        // param
        const mypageMasterId = document.querySelector('meta[name="mypage_master_id"]').content;




        var app = new Vue({


            el: '#app',

            /*
            | ------------------------------------------
            | data
            | ------------------------------------------
            */
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
                    strMax:'',
                    imageFile:''
                },

            }, //end data




            /*
            | ------------------------------------------
            | mounted
            | ------------------------------------------
            */
            mounted:function(){

                // editingTextboxの初期化
                this.editingTextbox = this.returnResetEditingTextbox();



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


            }, //end mounted




            /*
            | ------------------------------------------
            | methods
            | ------------------------------------------
            */
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

                    // textboxの表示変更
                    this.textboxes.forEach(thisTextbox => {

                        thisTextbox.mode = 'inoperable_textbox';

                    });


                    // 新規テキストボックスの挿入
                    this.editingTextbox = this.returnResetEditingTextbox();
                    this.textboxes.splice(index+1, 0, this.editingTextbox);

                    // 編集中テキストボックスデータの保存
                    this.editingIndex = index+1;


                    // エディターの表示変更
                    this.inputMode = 'create_textbox';

                    //エラー文のリセット
                    this.error = this.returnResetError();
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

                    //エラー文のリセット
                    this.error = this.returnResetError();

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
                    this.editingTextbox = this.returnResetEditingTextbox();

                    // エディターの表示変更
                    this.inputMode = '';
                },




                /**
                 * テキストボックスの保存(saveTextbox())
                 *
                 */
                 saveTextbox:function(){

                    console.log(this.editingTextbox);

                    //DBへ保存
                    switch (this.inputMode) {

                        case 'create_textbox':
                            let index = this.editingIndex;

                            // 非同期通信
                            fetch(ajax_store_textbox, {
                                method: 'POST',
                                body: new URLSearchParams({
                                    _token: token,
                                    case_name:this.editingTextbox.case_name,
                                    main_value: this.editingTextbox.main_value,
                                    sub_value: this.editingTextbox.sub_value,
                                    order: this.editingIndex,
                                }),
                            })
                            .then(response => response.json())
                            .then(json => {
                                this.textboxes[index].id = json.id ||[]; //textboxesテーブルのID登録
                            });

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
                    this.editingTextbox = this.returnResetEditingTextbox();

                    // エディターの表示変更
                    this.inputMode = '';
                },





                /**
                 * テキストボックスの削除(deleteTextbox())
                 *
                 */
                 deleteTextbox:function(textbox,index){
                    if( window.confirm('選択中のテキストボックスを削除しますか？') ){

                        // 非同期通信
                        fetch(ajax_destroy_textbox, {
                            method: 'POST',
                            body: new URLSearchParams({
                                _method: 'DELETE',
                                _token: token,
                                id:this.editingTextbox.id,
                                order: this.editingIndex,
                            }),
                        });


                        // プレビュー表示の削除
                        this.textboxes.splice(this.editingIndex, 1);

                        // textboxの表示変更
                        this.textboxes.forEach(textbox => {
                            textbox.mode = 'select_textbox';
                        });

                        // editingTextboxの初期化
                        this.editingTextbox = this.returnResetEditingTextbox();

                        // エディターの表示変更
                        this.inputMode = '';


                    }
                },



                /**
                 * 編集中テキストボックスの初期値を返す(returnResetEditingTextbox)
                 *
                 */
                 returnResetEditingTextbox:function(){

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









                /*
                | ---------------------------------------
                | エディター入力で実行されるchangeメソッド
                | ---------------------------------------
                */

                /**
                 * テキストボックスの種類選択の変更(changeTextboxCase())
                 *
                 */
                 changeTextboxCase:function(){

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

                        this.editingTextbox = this.returnResetEditingTextbox();
                        this.editingTextbox.case_name = editingTextboxCaseName;
                    }

                    //エラー文のリセット
                    this.error = this.returnResetError();


                    // テキストボックスのグループ名の更新
                    this.editingTextbox.group =　editingTextboxCaseGroup;

                    console.log(this.editingTextbox.group);


                },




                /**
                 * Heading要素入力(changeReplaceMainValue())
                 * main_valueの値を表示用(replace_main_value)に置き換える
                 */
                 changeReplaceMainValue: function(){

                    this.editingTextbox.main_value = this.validateStrMax(this.editingTextbox.main_value,100);



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
                 * imageファイル要素入力(changeImageFile())
                 *
                 */
                 changeImageFile: function(){

                    //バリデーションチェック
                    if(!this.validateImageFile()){ return; }

                    // 読込んだ画像ファイルをプレビューに表示
                    var reader = new FileReader(this.editingTextbox.image_url);
                    let imageFilePreview = document.getElementById('imageFilePreview');

                    reader.onload = function(){
                        imageFilePreview.src = reader.result;
                    }
                    let fileData = document.getElementById('imageFile').files;
                    reader.readAsDataURL(fileData[0]);



                    this.editingTextbox.image_url = imageFilePreview.src
                    console.log(this.editingTextbox.image_url);
                },




                /*
                | --------------------------
                | バリデーションメソッド
                | --------------------------
                */

                /**
                 * imageファイル入力のバリデーションvalidateImageFile())
                 *
                 * @return bool
                 */
                 validateImageFile: function(){

                    // 保存可能な条件
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

                        return false;

                    }else if(fileSize > maxFileSize){

                        this.error.imageFile = 'ファイルサイズが100kbを超えています。'
                        inputImageElement.value = '';

                        return false;

                    // エラーなしの処理
                    }else{
                        this.error.imageFile = ''
                        return true;
                    }

                },




                /**
                 * 文字数100文字以内のバリデーション(validateStrMax(str,num))
                 *
                 * @param String str
                 * @param Int num
                 * @return String num文字以上の文字を返す
                 */
                 validateStrMax: function(str,num){

                    if(str.length >num){

                        str = str.substring(0,num); //num文字以上の文字を削除
                        this.error.strMax = '文字数が'+num+'文字を超えて超えています'; //エラー文
                    }else{
                        this.error.strMax = '';
                    }

                    console.log(str+'('+str.length+')')
                    return str
                },


                /**
                 * エラー文の初期値を返す(returnResetError)
                 *
                 */
                 returnResetError:function(){
                    return { strMax:'', imageFile:'' };
                },

            }, //end methods
        });




    })();
</script>

