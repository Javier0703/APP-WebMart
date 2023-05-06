window.addEventListener("DOMContentLoaded",function (e){

    //sProd1 Buscador
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


    //sProd1 --> Filtro completo del precio
    let grid2 = document.querySelector(".sProd1>form>.grid2");
    let precio= grid2.nextElementSibling;
    grid2.addEventListener("click",function (e){
        precio.style.display="grid";
    });
    precio.addEventListener("click",function (e){
        precio.style.display="none";
    });
    precio.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });
    let sClose = document.querySelector(".sProd1>form>.typePrice .sPrice1").lastElementChild;
    sClose.addEventListener("click", function (e){
        precio.style.display="none";
    });

    let restablecer = document.querySelector(".mProd .typePrice .sPrice2").lastElementChild.firstElementChild;
    let aplicar = document.querySelector(".mProd .typePrice .sPrice2").lastElementChild.lastElementChild;
    let inputMin = document.getElementById("inputMin");
    let inputMax = document.getElementById("inputMax");

    let priceMin = document.getElementById("priceMin");
    let priceMax = document.getElementById("priceMax");

    inputMin.addEventListener("keypress",function (e){if (e.key === "Enter"){e.preventDefault(); inputMax.focus()}});
    inputMax.addEventListener("keypress",function (e){if (e.key === "Enter"){e.preventDefault();}});

    inputMin.addEventListener("keydown",function (e){
        if (e.key === '-' || e.key === 'e') {e.preventDefault();}
    });

    inputMax.addEventListener("keydown",function (e){
        if (e.key === '-' || e.key === 'e') {e.preventDefault();}
    });

    restablecer.addEventListener("click", function (e){
        inputMin.value="";
        inputMax.value="";
        inputMin.focus();
        grid2.classList.remove("typeExist");
        grid2.firstElementChild.firstElementChild.nextElementSibling.textContent="Precio";
        priceMin.name="";
        priceMin.value="";
        priceMax.name="";
        priceMax.value="";
    });

    aplicar.addEventListener("click",function (e){
        e.preventDefault();

        if (inputMin.value.length>0 && inputMax.value.length>0){
            if (inputMax.value - inputMin.value <0){
                let temp = inputMin.value;
                inputMin.value=inputMax.value;
                inputMax.value=temp;
            }
            grid2.classList.add("typeExist");
            grid2.firstElementChild.firstElementChild.nextElementSibling.textContent=inputMin.value+"€" + " - " + inputMax.value+"€";
            priceMin.name="price_min";
            priceMin.value=inputMin.value;
            priceMax.name="price_max";
            priceMax.value=inputMax.value;

        }

        else if (inputMin.value.length>0 || inputMax.value.length>0){

            if (inputMin.value.length>0){
                grid2.classList.add("typeExist");
                grid2.firstElementChild.firstElementChild.nextElementSibling.textContent="< "+inputMin.value+"€";
                priceMin.name="price_min";
                priceMin.value=inputMin.value;
            }

            else{
                grid2.classList.add("typeExist");
                grid2.firstElementChild.firstElementChild.nextElementSibling.textContent="> "+inputMax.value+"€";
                priceMax.name="price_max";
                priceMax.value=inputMax.value;
            }
        }


        else{
            grid2.firstElementChild.firstElementChild.nextElementSibling.textContent="Precio";
            grid2.classList.remove("typeExist");
            priceMin.name="";
            priceMin.value="";
            priceMax.name="";
            priceMax.value="";
        }

        precio.style.display="none";
    });

    //Popup de las categorías
    let grid3 = document.querySelector(".sProd1>form>.grid3");
    let cat = grid3.nextElementSibling;

    grid3.addEventListener("click", function (e){
        cat.style.display="grid";
    });
    cat.addEventListener("click",function (e){
        cat.style.display="none";
    });
    cat.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });
    cat.firstElementChild.firstElementChild.lastElementChild.addEventListener("click",function(e){
        cat.style.display="none";
    });

    let allCats = document.getElementById("allCats");
    let divsCats= document.querySelectorAll(".sProd1 .popUp>div>.sec2>div");
    divsCats.forEach(divsCat =>{
        divsCat.addEventListener("click" ,function (e){
            divsCat.nextElementSibling.style.display="block";
            divsCats.forEach(div =>{
                div.style.display="none";
                allCats.style.display="none";
            });
        });
    });

    let typeProd = document.getElementById("typeProd");
    let catProd = document.getElementById("catProd");
    let divsSub = document.querySelectorAll(".sProd1 .popUp .sec2>aside>div");

    divsSub.forEach(divSub =>{
        divSub.addEventListener("click",function (e){
            let id=divSub.id;

            if (divSub.classList.contains("typeAtr")){
                divSub.parentElement.style.display="none";
                divsCats.forEach(div =>{
                    div.style.display="flex";
                    allCats.style.display="block";
                });
            }

            else{
                typeProd.value=id;
                catProd.textContent=divSub.firstElementChild.textContent;
                grid3.classList.add("typeExist");

                if (divSub.classList.contains("typeCat")){
                    typeProd.name="id_cat";
                }
                else{
                    typeProd.name="id_sub";
                }

                divSub.parentElement.style.display="none";
                divsCats.forEach(div => {
                    div.style.display = "flex";
                    allCats.style.display="block";
                });
                cat.style.display="none";
            }

        });
    });

    allCats.addEventListener("click",function (e){
        typeProd.value="";
        typeProd.name="";
        catProd.textContent=allCats.firstElementChild.textContent;
        grid3.classList.remove("typeExist");
        cat.style.display="none";
    });

    //PopUp de order
    let div = document.querySelector(".mProd .ordenProd").firstElementChild;
    let divpopUp = document.querySelector(".mProd .ordenProd #popupOrder");
    let x = divpopUp.firstElementChild.firstElementChild.lastElementChild;

    div.addEventListener("click", function (e){
        divpopUp.style.display="grid";
    });
    divpopUp.addEventListener("click",function (e){
        divpopUp.style.display="none";
    });
    divpopUp.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });

    x.addEventListener("click", function (e){
        divpopUp.style.display="none";
    });

    let ps = document.querySelectorAll("#popupOrder>#secOrder>div>p");
    let form = document.querySelector(".mProd>.sProd1 form");
    let inputH = document.getElementById("orderHidden");
    ps.forEach(p =>{
        p.addEventListener("click",function (e){
            inputH.name="order";
            inputH.value=p.id;
            form.submit();
        });

    });

    //Escondemos todos los sections de productos menos el primero.
    let sections = document.querySelectorAll(".sProd2>section");
    sections.forEach(sec =>{
        sections[0].classList.add("showedS");
    });

    let bMas = document.querySelector(".sProd2>aside>button");

    let cent= 0;
    bMas.addEventListener("click",function (e){
        if (cent<sections.length-1){
            sections[cent].nextElementSibling.classList.add("showedS");
            cent++;
            if (cent===sections.length-1){
                bMas.parentElement.style.display="none";
            }
        }
    });

    //Mostrar mas


});