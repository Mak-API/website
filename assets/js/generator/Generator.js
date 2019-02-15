import $ from "jquery";

const onBlur = function(event) {
    const parent = this.parentNode;
    const value = this.value;
    const text = document.createTextNode(value);
    parent.removeChild(this);
    parent.removeChild(parent.getElementsByTagName('span')[0]);
    parent.appendChild(text);
    if(text.length > this.getAttribute('data-length')){
        parent.classList = 'red-text text-darken-2';
    }
};

const onClick = function(event) {
    const value = this.innerText;
    let input = document.createElement('input');
    $(input).attr({
        'type':'text',
        'data-length':'30',
        'id':'input-api-name',
    });
    input.value = value;
    this.innerText = '';
    input.addEventListener('blur', onBlur);
    this.appendChild(input);
    input.focus();

    M.CharacterCounter.init(document.getElementById('input-api-name'));
};

class Generator {
    static getOnClick(){
        return onClick;
    }
    static getOnBlur(){
        return onBlur;
    }
}

export default Generator;