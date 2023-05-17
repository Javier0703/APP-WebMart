
window.addEventListener("DOMContentLoaded",function (e){
    let eBoton = document.getElementById("dropProduct");
    let section = document.querySelector(".deleteProd");

    eBoton.addEventListener("click",function (e){
        e.preventDefault();
        section.style.display="block";
    });

    let nBoton = document.getElementById("noDelete");

    nBoton.addEventListener("click",function (e){
        e.preventDefault();
        section.style.display="none";
    });

});