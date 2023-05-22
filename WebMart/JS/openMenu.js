document.addEventListener("DOMContentLoaded",function (e){

    let desp = document.getElementById("desplegable");
    desp.addEventListener("click", function (e){
        document.querySelector("header>nav").classList.toggle("open");
    });

    document.querySelector("main").addEventListener("click",function (e){
        document.querySelector("header>nav").classList.remove("open");
    });

});