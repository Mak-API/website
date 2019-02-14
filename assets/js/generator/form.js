import $ from 'jquery';

const onBlur = function(event) {
    const parent = this.parentNode;
    const value = this.value;
    const text = document.createTextNode(value);
    parent.removeChild(this);
    parent.appendChild(text);
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

let formApiName = document.getElementById('form-api-name');

formApiName.addEventListener('click', onClick);