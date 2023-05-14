window.addEventListener("DOMContentLoaded",function (e){

    let input= document.querySelector(".mUser .flex>input");
    let close = document.querySelector(".mUser .flex>img:last-child");
    input.addEventListener("keyup", function (e){
        if (input.value.length>0){
            close.style.opacity="1";
        }
        else{
            close.style.opacity="0";
        }
    });
    close.addEventListener("click",function (e){
        input.value="";
        input.focus();
        close.style.opacity="0";
    });

    input.addEventListener("keydown", function (e){
        let key = e.key
        let alphaNum = /^[a-zA-Z0-9ñÑ_]$/.test(key);
        if (!(alphaNum || key === 'Enter' || key === 'Delete' || key === 'Backspace' || key == 'ArrowLeft' || key == 'ArrowRight')){
            e.preventDefault();
        }

    });



});