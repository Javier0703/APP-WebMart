<?php
include("conexDB.php");
$sesTime = 7 * 24 * 60 * 60;
session_set_cookie_params($sesTime);
session_start();

//Borramos si tenemos una COOKIE con mensaje
if (isset($_COOKIE["msg"])){setcookie("msg",false);}

//Cookies del usuario
if(isset($_SESSION["usu"]) && $_SESSION["pass"]){
    $con=conexUsu();
    $sql="SELECT COUNT(*) AS NUM, ROL,ESTADO FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
    $st=$con->prepare($sql);
    $st->bind_param("ss",$_SESSION["usu"],$_SESSION["pass"]);
    $st->execute();
    $st->bind_result($count,$rol,$estado);
    $st->fetch();
    if($count==1){
        if($estado==1){
            if($rol==0){
                $st->close();
                $con->close();
                //Eres usuario
                //Header a la página de USERS
                echo"eres user";
            }
            elseif($rol==1){
                $st->close();
                $con->close();
                //Eres administrador
                //Header a la página de ADMINS
                echo "eres admin";
            }
        }
        else{
            $st->close();
            $con->close();
            //Tu cuenta has sido bloqueada
            $msg="Tu cuenta ha sido bloqueada";
            setcookie("msg",$msg);
            header("Location:cierre.php");
        }
    }

    else{
        $st->close();
        $con->close();
        header("Location:cierre.php");
    }
}

//Login del usuario
else if (isset($_POST["usu"]) && strlen(trim($_POST["usu"]))>0 &&
        isset($_POST["pass"]) && strlen(trim($_POST["pass"]))>0){
    $usu=$_POST["usu"];
    $pass=$_POST["pass"];
    $pregName = preg_match('/^[a-zA-Z0-9_ñÑ]{5,30}$/',$usu);
    $pregEmail = preg_match('/^(?=.*[a-z])[a-zA-ZñÑ\d._-]+@[a-zA-Z0-9ñÑ]+\.[a-zA-ZñÑ]{5,40}$/',$usu);
    $pregPass = preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d+_*ñÑ-]{8,}$/',$pass);

    if ($pregName===0){
        if ($pregEmail===0){
            //Usuario no tiene las características pedidas
            $msg="Fallo email: Se permiten mayúsculas, minúsculas, números y _ (de 5 a 30 caracteres para el usuario) y el correo hasta 40 caracteres";
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
            $sql="SELECT USUARIO,CONTRASEÑA FROM usuarios WHERE USUARIO=? OR CORREO=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$usu);
            $st->execute();
            $st->bind_result($usuDB,$passDB);
            $st->fetch();

            if ($usuDB==$usu){
                //Hay que comparar la contraseña sea correcta
                if (password_verify("$pass","$passDB")===true){
                    echo "Prueba de que estamos dentro";
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
            if($numUsu===0){
                $st->close();
                $passRHash = password_hash("$passR",PASSWORD_BCRYPT);
                $rol=0; $estado=1;
                $sql="INSERT INTO usuarios(USUARIO,CONTRASEÑA,ROL,ESTADO) VALUES(?,?,?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("ssii",$usuR,$passRHash,$rol,$estado);
                $res=$st->execute();

                if ($res){
                    $st->close();
                    $con->close();

                    //Comprobar si quiere tener sesión iniciada
                    if (isset($_POST["sesionR"])){
                        $_SESSION["usu"]=$usuR;
                        $_SESSION["pass"]=$passRHash;
                    }
                    echo "guardado joya";

                }

                else{
                    $st->close();
                    $error="Ha habido un problema al introducir al usuario :C, pruebe de nuevo";
                    setcookie("error",$error);
                    $con->close();
                    header("Location:error.php");
                }
            }

            else{
                $st->close();
                $msg="Ese usuario ya existe, pruebe con otro";
                setcookie("msg",$msg);
                $con->close();
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