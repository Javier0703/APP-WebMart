window.addEventListener("DOMContentLoaded",function (e){

    let vender = document.getElementById("venderProducto");
    let form = document.getElementById("formSellProd");
    let close = document.getElementById("closeF");
    let nBut = document.getElementById("noEliminar");

    vender.addEventListener("click",function (e){
        form.style.display="flex";
    });

    form.addEventListener("click", function (e){
        form.style.display="none";
    });

    close.addEventListener("click",function (e){
        form.style.display="none";
    });

    nBut.addEventListener("click",function (e){
        e.preventDefault();
        form.style.display="none";
    });

    form.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });

});