window.addEventListener("DOMContentLoaded",function (e){
    let tit = document.getElementById("titulo");
    let titspan = document.getElementById("tit");

    let desc= document.getElementById("descripcion");
    let descspan = document.getElementById("desc");
    let precio= document.getElementById("precio");

    tit.addEventListener("input",function (e){
            titspan.textContent=tit.value.length;
    });

    desc.addEventListener("input",function (e){
        descspan.textContent=desc.value.length;
    });

    precio.addEventListener("keydown",function (e){
        if (e.key === '-' || e.key === 'e' || e.key === ',' || e.key === '.') {e.preventDefault();}
    });

    precio.addEventListener("input",function (e){
        if (precio.value<0){
            precio.value=0;
        }
        else if (precio.value>999999){
            precio.value=9999999
        }
    });

    let files = document.querySelectorAll(".sPublic3>section>input");
    files.forEach(file =>{

        file.addEventListener("change",function (e){
            if (this.value){
                file.classList.add("fileAdd");
            }

            else{
                file.classList.remove("fileAdd");
            }
        });
    });

    document.querySelector(".sPublic1").addEventListener("click",function (e){
        let p= document.querySelector(".sPublic1").lastElementChild;
        p.textContent="";
    });
    document.querySelector(".sPublic2").lastElementChild.previousElementSibling.addEventListener("click",function (e){
        document.querySelector(".sPublic2").lastElementChild.textContent="";
    });
    document.querySelector(".sPublic3").addEventListener("click",function (e){
        document.querySelector(".sPublic3").lastElementChild.textContent="";
    });

    let enviar= document.querySelector(".sPublic4>button");
    let inputHiddenP1 = document.getElementById("inputHidden");
    let inputs= document.querySelectorAll(".campoRellenar");

    enviar.addEventListener("click",function (e){
        if (inputHiddenP1.value===""){
            let p= document.querySelector(".sPublic1").lastElementChild;
            p.textContent="Elige la subcategoría";
        }

        let cent=0;
        inputs.forEach(input =>{

            if (input.value.trim().length===0){
                e.preventDefault();
                cent++;
                input.parentElement.classList.add("jsColorRed");
            }
            if (cent>0){
                document.querySelector(".pesaje").previousElementSibling.textContent="Rellene los campos";
            }
            cent=0;

            input.addEventListener("click", function (e){
                document.querySelector(".pesaje").previousElementSibling.textContent="";
                inputs.forEach(input =>{
                    input.parentElement.classList.remove("jsColorRed");
                });
            });
        });

        let peso = document.querySelectorAll("input[type='radio']");
        let cont=true;
        for (let i = 0; i < peso.length; i++) {
            if (peso[i].checked) {
                cont = false;
                break;
            }
        }
        if (cont){
            let cont=true;
            e.preventDefault();
            document.querySelector(".sPublic2").lastElementChild.textContent="Elija el peso";
        }

        let file = files[0];
        if (file.value.length===0){
            document.querySelector(".sPublic3").lastElementChild.textContent="Sube 1 foto al menos";
        }
    });

    let grid3 = document.querySelector(".sPublic1 .grid3");
    let g3pU = document.querySelector(".g3pU")

    let grid4 = document.querySelector(".sPublic1 .grid4");
    let g4pU = document.querySelector(".g4pU");

    let iHidden = document.getElementById("inputHidden");

    grid3.addEventListener("click",function (e){
        g3pU.style.display="grid";
    });

    g3pU.addEventListener("click", function (e){
        g3pU.style.display="none";
    });

    g3pU.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });

    g3pU.firstElementChild.firstElementChild.lastElementChild.addEventListener("click",function (e){
        g3pU.style.display="none";
    });

    let cats = document.querySelectorAll(".category");
    let subs = document.querySelectorAll(".subcategorias");

    cats.forEach(cat=>{

        cat.addEventListener("click",function (e){
            subs.forEach(sub =>{
                if (sub.classList.contains(cat.id)){
                    sub.style.display="flex";
                }
                else{
                    sub.style.display="none";
                }
            });

            g3pU.style.display="none";
            grid3.firstElementChild.textContent=cat.textContent;
            grid4.classList.remove("blocked");
            grid4.firstElementChild.textContent="Subcategorías";
            iHidden.value="";
        });
    });

    grid4.addEventListener("click",function (e){
        if (!grid4.classList.contains("blocked")){
            g4pU.style.display="grid";
        }
    });

    g4pU.addEventListener("click", function (e){
        g4pU.style.display="none";
    });

    g4pU.firstElementChild.addEventListener("click",function (e){
        e.stopPropagation();
    });

    g4pU.firstElementChild.firstElementChild.lastElementChild.addEventListener("click",function (e){
        g4pU.style.display="none";
    });

    subs.forEach(sub =>{
        sub.addEventListener("click", function (e){
            grid4.firstElementChild.textContent=sub.textContent;
            g4pU.style.display="none";
            iHidden.value=sub.id;
        });

    });

});