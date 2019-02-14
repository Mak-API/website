import $ from 'jquery';

let formApiName = document.getElementById('form-api-name');

const onBlur = function(event) {
    const value = this.value;
    const text = document.createTextNode(value);
    formApiName.removeChild(this);
    formApiName.appendChild(text);
};

const onClick = function(event) {
    const value = this.innerText;
    let input = document.createElement('input');
    input.className = 'test';
    input.value = value;
    this.innerText = '';
    input.addEventListener('blur', onBlur);
    this.appendChild(input);
    input.focus();
};

formApiName.addEventListener('click', onClick);