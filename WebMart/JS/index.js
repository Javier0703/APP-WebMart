window.addEventListener("DOMContentLoaded",function (e){

    let secLog= document.getElementById("login");
    let secReg= document.getElementById("registro");
    let pReg = secLog.firstElementChild.lastElementChild;
    let pLog = secReg.firstElementChild.lastElementChild;
    let mIndex= document.getElementById("mIndex");
    let nuevaP = mIndex.lastElementChild;

    //Inputs del Login
    let usu= document.getElementById("usu");
    let pass = document.getElementById("pass");

    //Inputs del Registro
    let usuR = document.getElementById("usuR");
    let passR = document.getElementById("passR");
    let passR2 = document.getElementById("passR2");

    let butLogin=pReg.previousElementSibling;
    let butRegister= pLog.previousElementSibling;

    //Botón de inicio de sesión
    butLogin.addEventListener("click", function (event){

        if (usu.value.trim().length === 0){
            event.preventDefault();
            //DAR COLOR ROJO
            nuevaP.textContent="Usuario se encuentra incompleto";
        }

        else{
            let regexUsuN= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
            let regexUsuE= /^(?=.*[a-z])[a-zA-ZñÑ\d._-]+@[a-zA-Z0-9ñÑ]+\.[a-zA-ZñÑ]{5,40}$/;

            if (regexUsuN.test(usu.value) === true){
                //Login como usuario
            }

            else if (regexUsuE.test(usu.value) === true){
                //Login como email
            }

            else{
                event.preventDefault();
                nuevaP.textContent="Se permiten mayúsculas, minúsculas, números y _ (de 5 a 30 caracteres para el usuario) y el correo hasta 40 caracteres";
            }
        }


        if (pass.value.trim().length === 0){
            //DAR COLOR ROJO
            event.preventDefault();
            nuevaP.textContent="Contraseña vacía";
        }

        else{
            let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
            if (regexPass.test(pass.value) === false){
                //Mostrar como debe de ir
                event.preventDefault();
                nuevaP.textContent="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
            }
        }

        if (usu.value.trim().length === 0 && pass.value.trim().length === 0){
            //Dar color
            nuevaP.textContent="Usuario y contraseña incompletos";
        }
    });

    //Botón de registro
    butRegister.addEventListener("click",function (event){

    //Comprobamos usuario
    if (usuR.value.trim().length === 0){
        event.preventDefault();
        //DAR COLOR ROJO AL INPUT
        nuevaP.textContent="Usuario se encuentra incompleto";
    }
    else{
        let regexUsu= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
        if (regexUsu.test(usuR.value) === false){
            event.preventDefault();
            //DAR COLOR NARANJA AL INPUT
            nuevaP.textContent="El usuario solo puede tener mayúsculas, minúsculas, números y _ y de 5 a 30 caracteres";

        }
    }

    //Comprobamos contraseña
    if (passR.value.trim().length === 0){
        event.preventDefault();
        //DAR COLOR ROJO
        nuevaP.textContent="Alguna contraseña está incompleta";
    }

    else{
        let regexPass= /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
        if (regexPass.test(passR.value) === false){
            event.preventDefault();
            //Mostrar como debe de ir
            nuevaP.textContent="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
        }
    }

    //Comprobamos contraseñas
    if (passR.value !== passR2.value){
        event.preventDefault();
        //Decir que las pass están distintas
        nuevaP.textContent="Las contraseñas son distintas";
    }

    if (usuR.value.trim().length === 0 && passR.value.trim().length === 0){
        //Decir que pasa
        nuevaP.textContent="Usuario y contraseña incompletos";
    }

    });

    let inputs = document.querySelectorAll("#mIndex>div>section>form>input")
    inputs.forEach(input =>{
        input.addEventListener("click", function (e){
            nuevaP.textContent="";
        });
    });

    pReg.addEventListener("click",function (e){
        usu.value='';
        pass.value='';
        secLog.classList.add("atras");
        secReg.classList.remove("atras");
        secReg.style.transition='1s';

    });

    pLog.addEventListener("click",function (e){
        usuR.value='';
        passR.value='';
        passR2.value='';
        secReg.classList.add("atras");
        secLog.classList.remove("atras");
        secLog.style.transition='1s';
    });
});