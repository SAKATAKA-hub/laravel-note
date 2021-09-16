'use strict';


//---------------------------------------------------------------
// 挿入するテキストボックスの種類を選択
//---------------------------------------------------------------

const inputTextBoxType = document.getElementById('inputTextBoxType');// テキストボックスの種類を選択するselect要素
const inputBoxs = document.querySelectorAll('.input_box');// 入力域
const editings = document.querySelectorAll('.editing');// プレビュー域


inputTextBoxType.onchange = ()=> {

    let typeKey = inputTextBoxType.value; //選択したテキストボックスの種類を関連付ける"キー"


    // 入力域の表示変更
    inputBoxs.forEach( (inputBox)=> {

        // 入力値を空に戻す
        // inputBox.querySelector('input[name="text"]').value = '';
        // inputBox.querySelector('input[name="subval"]').value = '';

        //クラスを最初に戻す
        inputBox.classList.add('hidden');



        if(  inputBox.id === typeKey )
        {
            inputBox.classList.remove('hidden');
        }

    });


    // プレビュー域の表示変更
    editings.forEach( (editing)=> {

        // 表示内容を空に戻す


        // クラスを最初に戻す
        editing.classList.add('hidden');

        if(  editing.classList.contains(typeKey) )
        {
            editing.classList.remove('hidden');
        }

    });


};



//---------------------------------------------------------------
// 入力内容をプレビューに反映させる
//---------------------------------------------------------------
inputBoxs.forEach( (inputBox)=>{

    let inputText = inputBox.querySelector('textarea[name="text"]');
    let inputSubval = inputBox.querySelector('input[name="subval"]');




    // textインプットに入力した時
    inputText.onchange = ()=>{
        console.log(inputBox.id);

        editings.forEach( (editing)=> {

            if(  editing.classList.contains(inputBox.id) )
            {
                editing.querySelector('.text').innerHTML = inputText.value.replace('{{', '<strong>').replace('}}', '</strong>');
            }

        });

    };



    // subvalインプットに入力した時
    inputSubval.onchange = ()=>{

        editings.forEach( (editing)=> {

            if(  editing.classList.contains(inputBox.id) )
            {
                if( inputBox.id === 'link')　//テキストボックスが'link'のとき
                {
                    editing.querySelector('.text').href = inputSubval.value;
                }
                else
                {
                    editing.querySelector('.subval').textContent = inputSubval.value;
                }
            }


        });

    };




});






//---------------------------------------------------------------
// 画像の処理
//---------------------------------------------------------------
//
// 添付画像の読み込み
// <input type="file" id="myImage" accept="image/*"
//    onchange="setImage(this);" onclick="this.value = '';">
// <img id="previewImage">
//---------------------------------------------------------------

//大きい画像の処理
function setImage(target) {
    var reader = new FileReader();
    reader.onload = function (e) {

        document.getElementById("previewImage").setAttribute('src', e.target.result);

    }
    reader.readAsDataURL(target.files[0]);
};

//小さい画像の処理
function setImageLitle(target) {
    var reader = new FileReader();
    reader.onload = function (e) {

        document.getElementById("previewImageLitle").setAttribute('src', e.target.result);

    }
    reader.readAsDataURL(target.files[0]);
};




