<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();


if (isset($_COOKIE["block"])){
    setcookie("block",false);
}

else{
    header("Location: productos.php");
}

if ((isset($_COOKIE["usu"]) && isset($_COOKIE["pass"])) || (isset($_SESSION["usu"]) && isset($_SESSION["pass"]))){

    if (isset($_COOKIE["usu"]) && $_COOKIE["pass"]){
        $usu=base64_decode($_COOKIE["usu"]);
        $pass=base64_decode($_COOKIE["pass"]);
    }

    if (isset($_SESSION["usu"]) && $_SESSION["pass"]){
        $usu=$_SESSION["usu"];
        $pass=$_SESSION["pass"];
    }

    if (conexUsu()==0){
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
                    $st->close();
                    $con->close();
                    header("Location: ../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    $st->close();
                    $con->close();
                    header("Location: ../index.php");
                }

                $st->close();
                $con->close();
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
    header("Location:../cierre.php");
}

?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../IMG/LOGOS_ERRORES/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,-25" />
    <title>WebMart</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>
<body>
<header>
    <a href="index.php"><img src="../IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>
    <nav>
        <section>
            <a href="#"><img src="../IMG/ICONS_NAV/estadisticas.png" alt="Estadisticas"><span>Estadísticas</span></a>
            <a href="#"><img src="../IMG/ICONS_NAV/grupo.png" alt="Usuarios"><span>Usuarios</span></a>
            <a href="#"><img src="../IMG/ICONS_NAV/agregar.png" alt="Subir Producto"><span>Subir un Producto</span></a>
            <?php
            $usu=USU;
            $con=conexUsu();
            $sql="SELECT ICONO FROM usuarios WHERE USUARIO=?";
            $st=$con->prepare($sql);
            $st->bind_param("s",$usu);
            $st->execute();
            $st->bind_result($icono);
            $st->fetch();
            echo '<div id="profileIcon"><img src="data:image/jpg;base64,'.base64_encode($icono).'"><span class="material-symbols-outlined">expand_more</span></div>';
            ?>
        </section>
        <div class="profile" id="profile">
            <div>
                <section><a href=""><span class="material-symbols-outlined">person</span><p>Perfil</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">favorite</span><p>Favoritos</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">shopping_cart</span><p>Favoritos</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">sell</span><p>Ventas</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>
    </nav>
</header>

<main class="mBlock">
    <section>
        <h3>¿Estás tramando cosas raras?</h3>
        <p>Es muy probable que si estás aquí es porque has intentado algo extraño</p>
        <p>Nosotros evitamos inyecciones SQL y XSS para evitar problemas ¡No lo hagas!</p>
        <a href="productos.php">Volver</a>
    </section>
</main>

<footer>
    <div>
        <p>Creado por Javier Calvo Porro</p>
        <table>
            <tr>
                <td><img id="github" src="../IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                <td>Github</td>
            </tr>
        </table>
    </div>
</footer>

</body>

<script src="../JS_APP/header.js"></script>
<script src="../JS_APP/prod.js"></script>
</html>