'use strict';
function modalInput(target)
{
    let input = document.getElementById('modalInputElement');
    input.value = target.value;
    console.log(input.value);
}
