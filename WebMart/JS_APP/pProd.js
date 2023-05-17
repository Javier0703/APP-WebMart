document.addEventListener("DOMContentLoaded",function (e){

    let images = document.querySelectorAll(".gridFyP .image img");

    let img = document.querySelector(".gridFyP .image img:first-child");
    img.classList.add("showed");

    let lSpan= document.querySelector(".image #left");
    lSpan.addEventListener("click", function (e){
        let imgActiva = document.querySelector(".gridFyP .image img.showed");
        let imgAnterior = imgActiva.previousElementSibling;

        if (imgAnterior!=null && imgAnterior.tagName.toLowerCase() === 'img'){
            imgActiva.classList.remove("showed");
            imgAnterior.classList.add("showed");
        }

        else{
            imgAnterior=images[images.length - 1]
            imgActiva.classList.remove("showed");
            imgAnterior.classList.add("showed");
        }

    });

    let rSpan= document.querySelector(".image #right");
    rSpan.addEventListener("click", function (e){
        let imgActiva = document.querySelector(".gridFyP .image img.showed");
        let imgSiguiente = imgActiva.nextElementSibling;

        if (imgSiguiente!=null && imgSiguiente.tagName.toLowerCase() === 'img'){
            imgActiva.classList.remove("showed");
            imgSiguiente.classList.add("showed");
        }

        else{
            imgSiguiente=images[0]
            imgActiva.classList.remove("showed");
            imgSiguiente.classList.add("showed");
        }


    });


});