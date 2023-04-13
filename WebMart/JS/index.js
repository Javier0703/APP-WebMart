window.addEventListener("DOMContentLoaded",function (e){

    let secLog= document.getElementById("login");
    let secReg= document.getElementById("registro");
    let pReg = secLog.firstElementChild.lastElementChild;
    let pLog = secReg.firstElementChild.lastElementChild;
    let indexDiv= document.getElementById("indexDiv");
    let mIndex= document.getElementById("mIndex");

    pReg.addEventListener("click",function (e){
        secLog.classList.add("atras");
        secReg.classList.remove("atras");
        secReg.style.transition='1s';

    });

    pLog.addEventListener("click",function (e){
        secReg.classList.add("atras");
        secLog.classList.remove("atras");
        secLog.style.transition='1s';
    });

    let butLogin=pReg.previousElementSibling;
    let butRegister= pLog.previousElementSibling;

    //Boton de inicio de sesión
    butLogin.addEventListener("click", function (event){
        let usu= document.getElementById("usu");
        let pass = document.getElementById("pass");

        if (usu.value.trim().length === 0){
            //DAR COLOR ROJO
            event.preventDefault();
        }
        else{
            let regexUsuN= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
            let regexUsuE= /^(?=.*[a-z])[a-zA-ZñÑ\d._-]+@[a-zA-Z0-9ñÑ]+\.[a-zA-ZñÑ]{5,40}$/;
            if (regexUsuN.test(usu.value) === true || regexUsuE.test(usu.value) === true){
                //Mostrar como debe de ir
                event.preventDefault();
            }
        }


        if (pass.value.trim().length === 0){
            //DAR COLOR ROJO
            event.preventDefault();
        }

        else{
            let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
            if (regexPass.test(pass.value) === false){
                //Mostrar como debe de ir
                event.preventDefault();
            }
        }
    });

    //Botón de registro

    butRegister.addEventListener("click",function (event){
        let usuR = document.getElementById("usuR");
        let passR = document.getElementById("passR");
        let passR2 = document.getElementById("passR2");

    //Comprobamos usuario
    if (usuR.value.trim().length === 0){
        event.preventDefault();
        //DAR COLOR ROJO AL INPUT
        let nuevaP=document.createElement("p");
        nuevaP.textContent="Usuario se encuentra incompleto";
        mIndex.append(nuevaP);
    }
    else{
        let regexUsu= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
        if (regexUsu.test(usuR.value) === false){
            event.preventDefault();
            //DAR COLOR NARANJA AL INPUT
            let nuevaP=document.createElement("p");
            nuevaP.textContent="El usuario solo puede tener mayúsculas, minúsculas, números y _ y de 5 a 30 caracteres";
            mIndex.append(nuevaP);
        }
    }

    //Comprobamos contraseña
    if (passR.value.trim().length === 0){
        event.preventDefault();
        //DAR COLOR ROJO
        let nuevaP=document.createElement("p");
        nuevaP.textContent="Alguna contraseña está incompleta";
        mIndex.append(nuevaP);
    }

    else{
        let regexPass= /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
        if (regexPass.test(passR.value) === false){
            event.preventDefault();
            //Mostrar como debe de ir
            let nuevaP=document.createElement("p");
            nuevaP.textContent="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
            mIndex.append(nuevaP);
        }
    }

    //Comprobamos contraseñas
    if (passR.value !== passR2.value){
        event.preventDefault();
        //Decir que las pass están distintas
        let nuevaP=document.createElement("p");
        nuevaP.textContent="Las contraseñas son distintas";
        mIndex.append(nuevaP);
    }

    if (usuR.value.trim().length === 0 && passR.value.trim().length === 0){
        //Decir que pasa
        let nuevaP=document.createElement("p");
        nuevaP.textContent="Usuario y contraseña incompletos";
        mIndex.append(nuevaP);
    }

    });

})