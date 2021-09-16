'use strict';


//---------------------------------------------------------------
// 公開・非公開　切り替えスイッチ
//---------------------------------------------------------------

const publishingInput = document.getElementById('inputPublishing');
const publishingLabel = document.querySelector('label[for="inputPublishing"]');

publishingInput.onchange = ()=> {


    if( publishingInput.checked )
    {
        publishingLabel.textContent = '公開';
        publishingLabel.classList.add('text-primary');
        publishingLabel.classList.add('fw-bold');
        // 'text-primary'
    }
    else{
        publishingLabel.textContent = '非公開';
        publishingLabel.classList.remove('text-primary');
        publishingLabel.classList.remove('fw-bold');
    }

};
