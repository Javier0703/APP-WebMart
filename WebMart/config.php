<?php
include("conexDB.php");
session_start();

//Registro del usuario
if(1!==1){
    echo "Hola";
}


//Registro del usuario
else if (isset($_POST["usuR"]) && strlen(trim($_POST["usuR"]))>0 &&
    isset($_POST["passR"]) && strlen(trim($_POST["passR"]))>0 &&
    isset($_POST["passR2"]) && strlen(trim($_POST["passR2"]))>0){

    $usuR=$_POST["usuR"]; $passR=$_POST["passR"]; $passR2=$_POST["passR2"];
    $pregName = preg_match('/^[a-zA-Z0-9_ñÑ]{5,30}$/',$usuR);
    $pregPass = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/',$passR);

    if ($pregName===0){
        //Usuario no tiene las características pedidas
        $msg="Usuario o contraseñas se encuentran incompletos";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    elseif ($pregPass===0){
        $msg="Usuario o contraseñas se encuentran incompletos";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    elseif ($passR!==$passR2){
        $msg="Las contraseñas no coinciden";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    else{
        echo "todo bien jefe";
    }

}


else{
    header("Location:index.php");
}