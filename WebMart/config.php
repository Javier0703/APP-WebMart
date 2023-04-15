<?php
include("conexDB.php");
session_set_cookie_params(sesTime());
session_start();

//Borramos si tenemos una COOKIE con mensaje
if (isset($_COOKIE["msg"])){setcookie("msg",false);}

//Sesiones del usuario
if(isset($_SESSION["usu"]) && $_SESSION["pass"]){
    $con=conexUsu();
    $sql="SELECT COUNT(*) AS NUM, ROL,ESTADO FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
    $st=$con->prepare($sql);
    $st->bind_param("ss",$_SESSION["usu"],$_SESSION["pass"]);
    $st->execute();
    $st->bind_result($count,$rol,$estado);
    $st->fetch();
    $st->close();
    $con->close();
    if($count==1){
        if($estado==1){
            if($rol==0){
                header("Location: W_USER/index.php");
            }
            elseif($rol==1){
                header("Location: W_ADMIN/index.php");
            }
        }
        else{
            //Tu cuenta has sido bloqueada
            $msg="Tu cuenta ha sido bloqueada";
            setcookie("msg",$msg);
            header("Location:cierre.php");
        }
    }

    else{
        header("Location:cierre.php");
    }
}

//Login del usuario
else if (isset($_POST["usu"]) && strlen(trim($_POST["usu"]))>0 &&
        isset($_POST["pass"]) && strlen(trim($_POST["pass"]))>0){
    $usu=$_POST["usu"];
    $pass=$_POST["pass"];
    $pregName = preg_match('/^[a-zA-Z0-9_ñÑ]{5,30}$/',$usu);
    $pregEmail = preg_match('/^(?=.{1,40}$)[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]{2,})+$/',$usu);
    $pregPass = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/',$pass);

    if ($pregName===0){
        if ($pregEmail===0){
            //Usuario no tiene las características pedidas
            $msg="Se permiten mayúsculas, minúsculas, números y _ (de 5 a 30 caracteres para el usuario) y el correo hasta 40 caracteres";
            setcookie("msg",$msg);
            header("Location:index.php");
        }
    }

    if ($pregPass===0){
        //Contraseña no cumple
        $msg="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    if(conexUsu()==2002){
        $cod=conexUsu();
        setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location:error.php");
    }

    else{
        try{
            $con=conexUsu();
            $sql="SELECT USUARIO,CORREO,CONTRASEÑA FROM usuarios WHERE USUARIO=? OR CORREO=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$usu);
            $st->execute();
            $st->bind_result($usuDB, $correoDB, $passDB);
            $st->fetch();
            $st->close();
            $con->close();
            if ($usuDB==$usu || $correoDB==$usu){
                //Hay que comparar la contraseña sea correcta
                if (password_verify("$pass","$passDB")===true){
                    $_SESSION["usu"]=$usuDB;
                    $_SESSION["pass"]=$passDB;
                    if (isset($_POST["sesion"])){
                        //Mantiene la sesión iniciada
                        setcookie("pasarela",1);
                    }
                    header("Location:pasarela.php");
                }

                else{
                    //La contraseña es incorrecta
                    $msg="Usuario o contraseña incorrecto";
                    setcookie("msg",$msg);
                    header("Location:index.php");
                }
            }

            else{
                //El usuario es incorrecto
                $msg="Usuario o contraseña incorrecto";
                setcookie("msg",$msg);
                header("Location:index.php");
            }

        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location:error.php");
        }
    }
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
        $msg="El usuario solo puede tener mayúsculas, minúsculas, números y _ y de 5 a 30 caracteres";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    elseif ($pregPass===0){
        //Contraseña no cumple
        $msg="La contraseña debe tener mayúsculas, minúsculas, y números (8 caracteres mínimo)";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    elseif ($passR!==$passR2){
        $msg="Las contraseñas no coinciden";
        setcookie("msg",$msg);
        header("Location:index.php");
    }

    else{
        if(conexUsu()==2002){
            $cod=conexUsu();
            setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
            header("Location:error.php");
        }
        try{
            $con=conexUsu();
            $sql="SELECT COUNT(USUARIO) FROM usuarios WHERE USUARIO=?";
            $st=$con->prepare($sql);
            $st->bind_param("s",$usuR);
            $st->execute();
            $st->bind_result($numUsu);
            $st->fetch();
            $st->close();
            $con->close();
            if($numUsu===0){
                $con=conexUsu();
                $passRHash = password_hash("$passR",PASSWORD_BCRYPT);
                $rol=0; $estado=1;
                $sql="INSERT INTO usuarios(USUARIO,CONTRASEÑA,ROL,ESTADO) VALUES(?,?,?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("ssii",$usuR,$passRHash,$rol,$estado);
                $res=$st->execute();
                $st->close();
                $con->close();
                if ($res){
                    //Comprobar si quiere tener sesión iniciada
                        $_SESSION["usu"]=$usuR;
                        $_SESSION["pass"]=$passRHash;
                        if (isset($_POST["sesionR"])){
                            //Mantiene la sesión iniciada
                            setcookie("pasarela",1);
                        }
                        header("Location:pasarela.php");

                }

                else{
                    $error="Ha habido un problema al introducir al usuario :C, pruebe de nuevo";
                    setcookie("error",$error);
                    header("Location:error.php");
                }
            }

            else{
                $msg="Ese usuario ya existe, pruebe con otro";
                setcookie("msg",$msg);
                header("Location:index.php");
            }
        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location:error.php");
        }
    }
}

else{
    header("Location:index.php");
}