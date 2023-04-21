window.addEventListener("DOMContentLoaded",function (e){

    let secLog= document.getElementById("login");
    let secReg= document.getElementById("registro");
    let pReg = document.getElementById("pReg");
    let pLog = document.getElementById("pLog");
    let mIndex= document.getElementById("mIndex");
    let nuevaP = mIndex.lastElementChild;

    //Inputs del Login
    let usu= document.getElementById("usu");
    let pass = document.getElementById("pass");

    //Inputs del Registro
    let usuR = document.getElementById("usuR");
    let passR = document.getElementById("passR");
    let passR2 = document.getElementById("passR2");

    let butLogin= document.getElementById("incSes");
    let butRegister= document.getElementById("Reg");

    //Botón de inicio de sesión
    butLogin.addEventListener("click", function (event){

        if (pass.value.trim().length === 0){
            event.preventDefault();
            nuevaP.textContent="Contraseña vacía";
            pass.parentElement.classList.add("colorRed");
        }

        else{
            let regexPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
            if (regexPass.test(pass.value) === false){
                event.preventDefault();
                nuevaP.textContent="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
                pass.parentElement.classList.add("colorOrange");
            }
        }

        if (usu.value.trim().length === 0){
            event.preventDefault();
            usu.parentElement.classList.add("colorRed");
            nuevaP.textContent="Usuario se encuentra incompleto";
        }

        else{
            let regexUsuN= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
            let regexUsuE= /^(?=.{1,40}$)[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]{2,})+$/;

            if (regexUsuN.test(usu.value) === true || regexUsuE.test(usu.value) === true){
                //Login correcto
            }

            else{
                event.preventDefault();
                usu.parentElement.classList.add("colorOrange");
                nuevaP.textContent="Se permiten mayúsculas, minúsculas, números y _ (de 5 a 30 caracteres para el usuario) y el correo hasta 40 caracteres";
            }
        }

        if (usu.value.trim().length === 0 && pass.value.trim().length === 0){
            usu.parentElement.classList.add("colorRed");
            pass.parentElement.classList.add("colorRed");
            nuevaP.textContent="Usuario y contraseña incompletos";
        }

    });

    //Botón de registro

    butRegister.addEventListener("click",function (event){

    //Comprobamos contraseña
        if (passR.value.trim().length === 0){
            event.preventDefault();
            passR.parentElement.classList.add("colorRed");
            passR2.parentElement.classList.add("colorRed");
            nuevaP.textContent="Alguna contraseña está incompleta";
        }

        else{
            let regexPass= /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/;
            if (regexPass.test(passR.value) === false){
                event.preventDefault();
                passR.parentElement.classList.add("colorOrange");
                passR2.parentElement.classList.add("colorOrange");
                nuevaP.textContent="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
            }
        }

    //Comprobamos usuario
        if (usuR.value.trim().length === 0){
            event.preventDefault();
            usuR.parentElement.classList.add("colorRed");
            nuevaP.textContent="Usuario se encuentra incompleto";
        }
        else{
            let regexUsu= /^[a-zA-Z0-9_ñÑ]{5,30}$/;
            if (regexUsu.test(usuR.value) === false){
                event.preventDefault();
                usuR.parentElement.classList.add("colorOrange");
                nuevaP.textContent="El usuario solo puede tener mayúsculas, minúsculas, números y _ y de 5 a 30 caracteres";

            }
        }

    //Comprobamos contraseñas
        if (passR.value !== passR2.value){
            event.preventDefault();
            passR.parentElement.classList.add("colorRed");
            passR2.parentElement.classList.add("colorRed");
            nuevaP.textContent="Las contraseñas son distintas";
        }

        if (usuR.value.trim().length === 0 && passR.value.trim().length === 0){
            usuR.parentElement.classList.add("colorRed");
            passR.parentElement.classList.add("colorRed");
            passR2.parentElement.classList.add("colorRed");
            nuevaP.textContent="Usuario y contraseña incompletos";
        }

    });

    //Cada vez que demos al input si hay un mensaje de error se borre
    let inputs = document.querySelectorAll("#mIndex form input[type='text'], #mIndex form input[type='password']");

    inputs.forEach(input =>{

        input.addEventListener("click", function (e){
            input.parentElement.classList.remove("colorRed");
            input.parentElement.classList.remove("colorOrange");
            nuevaP.textContent="";
        });
        input.addEventListener("keydown", function (e){
            input.parentElement.classList.remove("colorRed");
            input.parentElement.classList.remove("colorOrange");
        });

        input.addEventListener("keydown",function (e){
            nuevaP.textContent="";
        })

    });

    pReg.addEventListener("click",function (e){
        usu.value='';
        pass.value='';
        secLog.classList.add("atras");
        secReg.classList.remove("atras");
        secReg.style.transition='1s';

        inputs.forEach(input =>{
            nuevaP.textContent="";
            input.parentElement.classList.remove("colorRed");
            input.parentElement.classList.remove("colorOrange");
        });

    });

    pLog.addEventListener("click",function (e){
        usuR.value='';
        passR.value='';
        passR2.value='';
        secReg.classList.add("atras");
        secLog.classList.remove("atras");
        secLog.style.transition='1s';

        inputs.forEach(input =>{
            nuevaP.textContent="";
            input.parentElement.classList.remove("colorRed");
            input.parentElement.classList.remove("colorOrange");
        });

    });

});