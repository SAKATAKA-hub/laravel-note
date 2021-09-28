'use strict';

// ----------------------------------------------
// 公開設定スイッチの切り替え
// ----------------------------------------------

const publishingInput = document.getElementById('inputPublishing');
const publishingLabel = document.querySelector('label[for="inputPublishing"]');

publishingInput.onchange = ()=> {

    if( publishingInput.checked )
    {
        publishingLabel.textContent = '公開';
        publishingLabel.classList.add('text-primary');
        publishingLabel.classList.remove('text-secondary');
        // 'text-primary'
    }
    else{
        publishingLabel.textContent = '非公開';
        publishingLabel.classList.remove('text-primary');
        publishingLabel.classList.add('text-secondary');
    }

};

// ----------------------------------------------
// プレビュー画面へのレンダリング
// ----------------------------------------------

// タイトル
const inputNoteTitle = document.getElementById('inputNoteTitle');
const noInputNoteTitle = ''
// document.getElementById('noteTitle').textContent = noInputNoteTitle;

inputNoteTitle.onchange = ()=>{
    if(inputNoteTitle.value !== '')
    {
        document.getElementById('noteTitle').textContent = inputNoteTitle.value;
    }
    else
    {
        document.getElementById('noteTitle').textContent = noInputNoteTitle;
    }
};


// テーマカラー
const inputNoteColor = document.getElementById('inputNoteColor');
inputNoteColor.onchange = ()=>{
    const previewContainer = document.getElementById('previewContainer');
    previewContainer.className = 'preview_note_container display_note_container_'+inputNoteColor.value;
};


// タグ
const tags = document.querySelectorAll('input[name="tags[]"]');
const newTags = document.getElementById('newTags');
const noInputTags = ''
// document.getElementById('noteTag').textContent =  noInputTags;

tags.forEach( changeTag => {
    changeTag.onchange = ()=>{

        changeTags();　// *changeTags関数

    };
} );


// *changeTags関数
function changeTags()
{
    const tagsArray = [];

    // チェックボックスの入力値
    tags.forEach( tag => {

        // console.log(tag.value);
        if(tag.checked)
        {
            tagsArray.push(tag.value);
        }
    } );

    //インプットボックスの入力値
    if(newTags.value!=='')
    {
        tagsArray.push( newTags.value.replace(/ /g, '　') );
    }


    //入力値の挿入
    if(tagsArray.length)
    {
        document.getElementById('noteTag').textContent =  tagsArray.join('　');
        tags[0].required = ""; //requiredを無効にする。
    }
    else
    {
        document.getElementById('noteTag').textContent =  noInputTags;
        tags[0].required = "required"; //requiredを有効にする。
    }
}




