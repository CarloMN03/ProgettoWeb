function closeForm(ʌ, form, v){
    document.getElementById(ʌ).style.display = "none";
    document.getElementById(form).style.display = "none";
    document.getElementById(v).style.display = "initial";
}

function openForm(ʌ, form, v){
    document.getElementById(ʌ).style.display = "initial";
    document.getElementById(form).style.display = "inherit";
    document.getElementById(v).style.display = "none";
}
