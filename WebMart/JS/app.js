window.addEventListener("DOMContentLoaded",function (e){

    let sIndex2Form = document.querySelector(".sIndex2>form");


    //Section 3 ≥ Clic en div y coger su elemento para ponerlo en el input hidden y mandar formulario.
    let sIndex3Form = document.querySelector(".sIndex3>form");
    let divs = document.querySelectorAll(".sIndex3>form>div");
    divs.forEach(div =>{
        div.addEventListener("click", function (e){
            let valor= div.id;
            sIndex3Form.lastElementChild.value=div.id;
            sIndex3Form.submit();
        });
    });




});