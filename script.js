function checkX() {
    const xRadio = document.getElementById("xRadio");
    return !isNaN(parseFloat(xRadio.value));
}

function checkY() {
    const yText = document.getElementById("yText");
    if (!(isNaN(parseFloat(yText.value)) || yText.value.trim().length > 5 || yText.value.trim() !== parseFloat(yText.value.trim()).toString())) {
        let tmp = parseFloat(yText.value);
        return tmp > -3.0 && tmp < 5.0;
    }
    return false;
}

function checkR() {
    const rCheckbox = document.getElementsByName("rCheckbox[]");
    let cnt = 0;
    for (let i = 0; i < rCheckbox.length; ++i) {
        if (rCheckbox.item(i).checked) ++cnt;
    }
    return cnt === 1;
}

function check() {
    let submitButton = document.getElementById("submitButton");
    submitButton.disabled = !(checkX() && checkY() && checkR());
}

let checkTimer = setInterval(check, 30);