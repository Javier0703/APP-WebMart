window.addEventListener("DOMContentLoaded",function (e){

    let inputG1= document.querySelector(".sProd1 .grid1>input");
    let close = document.querySelector(".sProd1 .grid1>img:last-child");

    inputG1.addEventListener("keyup", function (e){
        if (inputG1.value.length>0){
            close.style.opacity="1";
        }
        else{
            close.style.opacity="0";
        }
    });

    close.addEventListener("click",function (e){
        inputG1.value="";
        inputG1.focus();
        close.style.opacity="0";
    });

});