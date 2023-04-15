<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();


if (isset($_SESSION["usu"]) && isset($_SESSION["pass"])){

    if (conexUsu()==2002){
        $cod=conexUsu();
        setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location:error.php");
    }
    else{

        try {
            $con=conexUsu();
            $sql="SELECT ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$_SESSION["usu"],$_SESSION["pass"]);
            $st->execute();
            $st->bind_result($estado,$rol);

            if ($st->fetch()){

                if ($rol==1){
                    header("Location: ../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    header("Location: ../index.php");
                }

                define("USU",$_SESSION["usu"]);
                define("PASS",$_SESSION["pass"]);

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

elseif (isset($_COOKIE["usu"]) && isset($_COOKIE["pass"])){
    $usu=base64_decode($_COOKIE["usu"]);
    $pass=base64_decode($_COOKIE["pass"]);

    if (conexUsu()==2002){
        $cod=conexUsu();
        setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location:error.php");
    }

    else{

        try {
            $con=conexUsu();
            $sql="SELECT ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($estado,$rol);

            if ($st->fetch()){

                if ($rol==1){
                    header("Location: ../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    header("Location: ../index.php");
                }

                define("USU",$usu);
                define("PASS",$pass);

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
    header("Location: ../index.php");
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../IMG/logo.png">
    <title>WebMart</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>

<body>

<header>
    <a href="index.php"><img src="../IMG/logoEntero.png" alt="Logo"></a>
    <nav>
        <ul>
            <a href="#"><li><img src="../IMG/ICONS/estadisticas.png" alt="Estadisticas"></li></a>
            <a href="#"><li><img src="../IMG/ICONS/grupo.png" alt="Usuarios"></li></a>
            <a href="#"><li><img src="../IMG/ICONS/agregar.png" alt="Subir Producto"></li></a>
        </ul>
    </nav>

    <div>
        <img src="../IMG/ICONS/usuario.png" alt="Perfil">
    </div>

</header>
</body>
</html>