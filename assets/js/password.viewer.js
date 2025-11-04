function pass_show_hide(){
    var x = document.getElementById("password");
    var showeye = document.getElementById("showeye");
    var hideeye = document.getElementById("hideeye");
    showeye.classList.remove("d-none");

    if (x.type == "password") {
        x.type= "text";
        showeye.style.display = "block";
        hideeye.style.display = "none";
    } else {
        x.type= "password";
        showeye.style.display = "none";
        hideeye.style.display = "block";
    }
}