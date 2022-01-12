<script>

(function(){
    'use strict';
    // token
    const token = document.querySelector('meta[name="token"]').content;
    // route
    const jsonNote = document.querySelector('meta[name="json_note"]').content;
    const update_note =  document.querySelector('meta[name="update_note"]').content;
    const ajax_store_textbox = document.querySelector('meta[name="ajax_store_textbox"]').content;
    const ajax_update_textbox = document.querySelector('meta[name="ajax_update_textbox"]').content;
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
            inputMode: '',
            /*
            * 'create_titlebox' : ノートの新規作成
            * 'edit_titlebox'   : ノートの編集
            * 'create_textbox'  : テキストボックスの新規作成
            * 'edit_textbox'    : テキストボックスの編集
            * '' : テキストボックスの選択中
            */


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

            // ノート編集時の追加情報
            newTagsString : '', //新しく追加されたタグ

            //<-- セレクトボックスの選択要素-->
            selects :{
                colors : '',
                tags : '',
                textbox_cases : '',
            },

            //<-- バリデーションのエラーメッセージ -->
            error:[],


        }, //end data




        /*
        | ------------------------------------------
        | mounted
        | ------------------------------------------
        */
        mounted:function(){

            // editingTextboxの初期化
            this.editingTextbox = this.returnResetEditingTextbox();
            //エラー文の初期化
            this.error = this.returnResetError();

            // 非同期通信
            fetch(jsonNote, {
                method: 'POST',
                body: new URLSearchParams({
                    _token: token,
                    mypage_master_id: mypageMasterId,
                }),
            })
            .then(response => {
                if(!response.ok){ throw new Error(); }
                return response.json();
            })
            .then(json => {

                this.note = json.note ||[]; //noteデータ
                this.textboxes = json.textboxes ||[]; //textboxデータ
                this.selects = json.selects ||[]; //セレクト要素

                console.log(json);

                // ノートの新規作成のとき、新規作成フォームの表示
                if(this.textboxes[0].mode === 'editing_textbox'){
                    this.editTitlebox();
                    this.inputMode =  'create_titlebox';
                }
            })
            .catch(error => {
                alert('データの読み込みに失敗しました。');
            });



        }, //end mounted




        /*
        | ------------------------------------------
        | methods
        | ------------------------------------------
        */
        methods:{

            /**
             * テキストボックス新規挿入フォームの表示(createTextbox(index))
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

                //エラー文の初期化
                this.error = this.returnResetError();
            },



            /**
             * テキストボックス編集フォームの表示(editTextbox(textbox,index))
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


                // エディターの表示変更
                this.inputMode = 'edit_textbox';

                //エラー文の初期化
                this.error = this.returnResetError();

                console.log(this.editingTextbox);
            },




            /**
             * タイトルボックス編集フォームの表示(editTitlebox(textbox,index))
             *
             */
            editTitlebox:function(){

                const index = 0;
                // 新規テキストボックスの削除
                if(this.inputMode === 'create_textbox'){
                    this.textboxes.splice(this.editingIndex, 1);
                }

                // 編集中テキストボックスデータの保存
                this.editingIndex = index;
                this.editingTextbox = Object.assign({}, this.note);

                // textboxの表示変更
                this.textboxes.forEach( (thisTextbox,i) => {
                    thisTextbox.mode = index === i? 'editing_textbox': 'inoperable_textbox';
                });


                // エディターの表示変更
                /*
                 * 'create_titlebox' : ノートの新規作成
                 * 'edit_titlebox'   : ノートの編集
                */
                this.inputMode = this.inputMode !== 'create_titlebox'? 'edit_titlebox': 'create_titlebox';


                //エラー文の初期化
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

                // テーマカラーを元に戻す
                if(this.inputMode === 'edit_titlebox'){
                    let color = this.editingTextbox.color;
                    this.note.color = color;
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
                 console.log(this.editingTextbox.group);

                // 入力要素の文字数バリデーション
                let validate = true;
                if(this.editingTextbox.group !== 'text'){

                    // エラー文を初期値に戻す。
                    this.error = this.returnResetError();

                    let key ='';
                    const num = 100; //最大文字数

                    key = 'main_value_input';
                    if(this.validateStrMax(this.editingTextbox[key],num)){
                        this.error[key] = "文字数が"+num+"文字を超えて超えています。";
                        validate = false;
                    }

                    key = 'sub_value';
                    if(this.validateStrMax(key,num)){
                        this.error[key] = "文字数が"+num+"文字を超えて超えています。";
                        validate = false;
                    }
                }


                // バリデーションに通過したとき、
                if(validate){


                    //DBへ保存(非同期通信)
                    switch (this.inputMode) {

                        // ------ 新規作成 ------
                        case 'create_textbox':

                            let index = this.editingIndex;
                            fetch(ajax_store_textbox, {
                                method: 'POST',
                                body: new URLSearchParams({
                                    _token: token,
                                    case_name:this.editingTextbox.case_name,
                                    main_value: this.editingTextbox.main_value_input,
                                    sub_value: this.editingTextbox.sub_value,
                                    order: this.editingIndex,
                                }),
                            })
                            .then(response => {
                                if(!response.ok){ throw new Error(); }
                                return response.json();
                            })
                            .then(json => {
                                // idの登録
                                this.textboxes[index].id = json.id;
                            })
                            .catch(error => {
                                alert('通信エラーが発生しました。ページを再読み込みします。');
                                location.reload();
                            });
                            break;


                        // ------ 更新 ------
                        case 'edit_textbox':

                            // editingTextbox.idの更新がうまくいっていないとき
                            if(!this.editingTextbox.id){
                                alert('データ更新エラーが発生しました.');
                                return;
                            }

                            fetch(ajax_update_textbox, {
                                method: 'POST',
                                body: new URLSearchParams({
                                    _method: 'PATCH',
                                    _token: token,
                                    id:this.editingTextbox.id,
                                    case_name:this.editingTextbox.case_name,
                                    main_value: this.editingTextbox.main_value_input,
                                    sub_value: this.editingTextbox.sub_value,
                                    order: this.editingIndex,
                                }),
                            })
                            .then(response => {
                                if(!response.ok){ throw new Error(); }
                            })
                            .catch(error => {
                                alert('通信エラーが発生しました。ページを再読み込みします。');
                                location.reload();
                            });
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

                    //エラー文の初期化
                    this.error = this.returnResetError();


                } //end if(validate)
            },




            /**
             * 画像テキストボックスの保存(saveImageTextbox())
             *
             */
             saveImageTextbox:function(e){
                // e.preventDefault();

                // 'edit_textbox'modeのとき、idをリクエストする。
                if(this.inputMode==='edit_textbox'){

                    // editingTextbox.idの更新がうまくいっていないとき
                    if(!this.editingTextbox.id){
                        e.preventDefault();
                        alert('データ更新エラーが発生しました.');
                        return;
                    }
                    document.querySelector('input[name="id"]').value = this.editingTextbox.id;
                }

                // リクエストの値を代入する
                document.querySelector('input[name="order"]').value = this.editingIndex;
                document.querySelector('input[name="case_name"]').value = this.editingTextbox.case_name;
                document.querySelector('input[name="group"]').value = this.editingTextbox.group;
                document.querySelector('input[name="old_image"]').value = this.textboxes[this.editingIndex].main_value;
             },






            /**
             * テキストボックスの削除(deleteTextbox())
             *
             */
             deleteTextbox:function(textbox,index){

                // editingTextbox.idの更新がうまくいっていないとき
                if(!this.editingTextbox.id){
                    alert('データ更新エラーが発生しました.');
                    return;
                }


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
                    })
                    .then(response => {
                        if(!response.ok){ throw new Error(); }
                    })
                    .catch(error => {
                        alert('通信エラーが発生しました。ページを再読み込みします。');
                        location.reload();
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
                    main_value_input: '',
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

                // テキストボックスのidの保存(textboxId)
                const editingTextboxId = this.editingTextbox.id;

                //テキストボックスの種類名の保存(editingTextboxCaseName)
                const editingTextboxCaseName = this.editingTextbox.case_name;

                // テキストボックスのグループの保存(editingTextboxCaseGroup)
                let editingTextboxCaseGroup = '';
                this.selects.textbox_cases.forEach(textbox_case => {
                    if(textbox_case.value === editingTextboxCaseName)
                    {
                        editingTextboxCaseGroup = textbox_case.group;
                    }
                });


                // テキストボックス情報(テキストボックスのグループが変更するとき)
                if(this.editingTextbox.group !== editingTextboxCaseGroup){

                    // テキストボックス情報のリセット
                    this.editingTextbox = this.returnResetEditingTextbox();

                    // テキストボックス保存情報の更新
                    this.editingTextbox.id = editingTextboxId;
                    this.editingTextbox.case_name = editingTextboxCaseName;
                    this.editingTextbox.group = editingTextboxCaseGroup;
                }

                //エラー文の初期化
                this.error = this.returnResetError();



                console.log(this.editingTextbox);
                // console.log(this.textboxes[this.editingIndex]);

            },




            /**
             * Heading要素入力(changeReplaceMainValue())
             * main_valueの値を表示用(replace_main_value)に置き換える
             */
             changeReplaceMainValue: function(){

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
                let replace_value = replaceValue(this.editingTextbox.main_value_input);
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

                // ファイルデータをURL形式に変換
                var reader = new FileReader(this.editingTextbox.image_url);
                let imageFilePreview = document.getElementById('imageFilePreview');
                let fileData = document.getElementById('imageFile').files;
                reader.readAsDataURL(fileData[0]);

                // 読込んだ画像ファイルをプレビューに表示
                let editingTextbox = this.editingTextbox; //編集中テキストボックス
                reader.onload = function(){
                    imageFilePreview.src = reader.result;
                    editingTextbox.image_url = reader.result;
                }
                console.log(this.editingTextbox);

            },




            /*
            | --------------------------
            | バリデーションメソッド
            | --------------------------
            */

            /**
             * ノート基本情報更新時のバリデーションvalidateImageFile())
             *
             */
             checkTitleForm: function(e){
                if( !this.editingTextbox.tags_array.length && !this.newTagsString ){
                    e.preventDefault();
                    this.error.tag = 'タグを一つ以上選択して下さい。';
                }
            },




            /**
             * imageファイル入力のバリデーションvalidateImageFile())
             *
             * @return bool
             */
             validateImageFile: function(){

                // 保存可能な条件
                const fileTypesArray = ['jpeg','png','jpg','gif'];
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
             * @return Boolean
             */
             validateStrMax: function(editingTextbox_caram,num){


                if(editingTextbox_caram.length >num){
                    return true;
                }else{
                    return false;
                }
            },


            /**
             * エラー文の初期値を返す(returnResetError)
             *
             */
             returnResetError:function(){
                return {imageFile:'', tag:'',main_value_input:'',sub_value:'',};
            },

        }, //end methods
    });




})();
</script>

