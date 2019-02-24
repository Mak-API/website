import $ from "jquery";
import M from "materialize-css";

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

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.collapsible');
    var instances = M.Collapsible.init(elems);
});

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems);
});

/**
 * @description: Display a box with additional data
 */
let btnBox = document.getElementById('add-attr_');
let btnBoxValid = document.getElementById('valid-attr');
let btnBoxCancel = document.getElementById('cancel-attr');


btnBox.addEventListener('click', function(){
    document.getElementById('box_over-file_').classList.remove("hide");
    document.getElementById('box_over-file_').classList.add("box-placement");
});

/**
 * @description: Hide the last box with valid btn
 */
btnBoxValid.addEventListener("click", function(){
    document.getElementById('box_over-file_').classList.add("hide");
});

/**
 * @description: Hide the last box with cancel btn
 */
btnBoxCancel.addEventListener("click", function(){
    document.getElementById('box_over-file_').classList.add("hide");
});

export default Generator;