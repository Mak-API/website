import $ from "jquery";
import M from 'materialize-css';

const onBlurApiName = function() {
    const parent = this.parentNode;
    let value = this.value;
    parent.removeChild(this);
    parent.removeChild(parent.getElementsByTagName("span")[0]);
    if(value.length > this.getAttribute("data-length")){
        value = value.substr(0, this.getAttribute("data-length"));
        parent.classList = "red-text text-darken-2";
    }
    const text = document.createTextNode(value);
    parent.appendChild(text);
};

const onClickApiName = function() {
    const value = this.innerText;
    let input = document.createElement("input");
    $(input).attr({
        "type":"text",
        "data-length":"25",
        "id":"input-api-name",
    });
    input.value = value;
    this.innerText = "";
    input.addEventListener("blur", onBlurApiName);
    this.appendChild(input);
    input.focus();

    M.CharacterCounter.init(document.getElementById("input-api-name"));
};

class Generator {
    static getOnClickApiName(){
        return onClickApiName;
    }
    static getOnBlurApiName(){
        return onBlurApiName;
    }
}

export default Generator;