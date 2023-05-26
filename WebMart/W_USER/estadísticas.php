<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

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
        header("Location: ../error.php");
    }

    else{

        try {
            $con=conexUsu();
            $sql="SELECT ID_USU,ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($idDB,$estado,$rol);

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
                define("IDUSU",$idDB);

            }
        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location: ../error.php");
        }
    }
}

else{
    header("Location: ../cierre.php");
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../IMG/LOGOS_ERRORES/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,-25"/>
    <title>WebMart</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>

<body>

<header>

    <a id="logo" href="index.php"><img src="../IMG/LOGOS_ERRORES/logo.png" alt="Logo"></a>
    <a id="logoE" href="index.php"><img src="../IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>

    <nav>

        <section >

            <img id="fDesplig" src="../IMG/LOGOS_ERRORES/despleg.png" alt="Logo">

            <section>
                <a href="estadísticas.php"><img src="../IMG/ICONS_NAV/estadisticas.png" alt="Estadisticas"><span>Estadísticas</span></a>
                <a href="usuarios.php"><img src="../IMG/ICONS_NAV/grupo.png" alt="Usuarios"><span>Usuarios</span></a>
                <a href="publicar.php"><img src="../IMG/ICONS_NAV/agregar.png" alt="Subir Producto"><span>Subir un Producto</span></a>
            </section>


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
            $st->close();
            ?>
        </section>

        <div class="profile" id="profile">
            <div>
                <section><a href="perfil/perfil.php"><span class="material-symbols-outlined">person</span><p>Perfil</p></a></section>
                <section><a href="perfil/productos.php"><span class="material-symbols-outlined">sell</span><p>Productos</p></a></section>
                <section><a href="perfil/compras.php"><span class="material-symbols-outlined">shopping_cart</span><p>Compras</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">favorite</span><p>Favoritos</p></a></section>
                <section><a href="perfil/misOpiniones.php"><span class="material-symbols-outlined">rate_review</span><p>Mis Opiniones</p></a></section>
                <section><a href="perfil/opiniones.php"><span class="material-symbols-outlined">edit_note</span><p>Opiniones</p></a></section>
                <section><a href="perfil/reservas.php"><span class="material-symbols-outlined">bookmarks</span><p>Reservas</p></a></section>
                <section><a href="perfil/mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>

    </nav>

</header>

<main class="mEstadisticas">

    <section class="tit">
        <p>Estadísticas generales de la aplicación</p>
    </section>

    <section class="info">

        <div class="upProducts">
            <p>Usuarios con más productos subidos</p>
            <canvas id="myChart"></canvas>
            <section class="datos">
                <?php
                $con=conexUsu();
                $sql="SELECT USUARIO, count(ID_PROD) PRODUCTOS FROM usuarios u left outer join productos p USING (ID_USU) GROUP BY ID_USU ORDER BY PRODUCTOS DESC limit 3";
                $res = $con->query($sql);
                $fila = $res->fetch_assoc();
                while ($fila){
                    ?>
                    <p>
                        <span><?=$fila["USUARIO"]?></span>
                        <span><?=$fila["PRODUCTOS"]?></span>
                    </p>
                <?php
                    $fila=$res->fetch_assoc();
                }
                $con->close();
                ?>
            </section>
        </div>

        <div class="buyProducts">
            <p>Usuarios con más compras realizadas</p>
            <canvas id="myChart2"></canvas>
            <section class="datos">
                <?php
                $con=conexUsu();
                $sql="SELECT u.USUARIO, count(p.ID_USU) COMPRAS from productos p RIGHT OUTER JOIN usuarios u on p.ID_COMPRADOR = u.ID_USU GROUP BY u.ID_USU LIMIT 3";
                $res = $con->query($sql);
                $fila = $res->fetch_assoc();
                while ($fila){
                    ?>
                    <p>
                        <span><?=$fila["USUARIO"]?></span>
                        <span><?=$fila["COMPRAS"]?></span>
                    </p>
                    <?php
                    $fila=$res->fetch_assoc();
                }
                $con->close();
                ?>
            </section>
        </div>

    </section>

    <section class="tit">
        <p>Estadísticas personales</p>
    </section>

    <section class="info personal">

        <div class="leyenda">
            <section>
                <div>
                    <span style="background: #2898ee; color: #2898ee" class="material-symbols-outlined">Done</span><p>Productos subidos</p>
                </div>

                <div>
                    <span style="background: #107acc; color: #107acc" class="material-symbols-outlined">Done</span><p>Compras realizadas</p>
                </div>

                <div>
                    <span style="background: #0cbccc; color: #0cbccc" class="material-symbols-outlined">Done</span><p>Ventas obtenidas</p>
                </div>

                <div>
                    <span style="background: #15297c; color: #15297c" class="material-symbols-outlined">Done</span><p>Reseñas realizadas</p>
                </div>

                <div>
                    <span style="background: #142157; color: #142157" class="material-symbols-outlined">Done</span><p>Mensajes enviados</p>
                </div>
            </section>

        </div>

        <div id="personal">
            <p class="noResultsGiven">Parece que eres nuevo en la aplicación y no tienes acciones :(</p>
            <canvas id="myChart3"></canvas>
            <section class="datos" id="datosPersolanes">
                <?php
                $id=IDUSU;
                $con= conexUsu();
                $sql="SELECT COUNT(ID_PROD) NUM FROM productos WHERE ID_USU=$id";
                $res= $con->query($sql);
                $f = $con->query($sql)->fetch_assoc();
                ?>
                <span><?=$f["NUM"]?></span>
                <?php
                $res->close();
                $sql="SELECT COUNT(ID_PROD) NUM from productos where ID_COMPRADOR=$id";
                $res= $con->query($sql);
                $f = $con->query($sql)->fetch_assoc();
                ?>
                <span><?=$f["NUM"]?></span>
                <?php
                $res->close();
                $sql="SELECT COUNT(ID_PROD) NUM FROM productos p WHERE ID_USU=$id AND ID_COMPRADOR is NOT null";
                $res= $con->query($sql);
                $f = $con->query($sql)->fetch_assoc();
                ?>
                <span><?=$f["NUM"]?></span>
                <?php
                $res->close();
                $sql="SELECT COUNT(ID_PROD) NUM FROM opiniones WHERE ID_USU=$id";
                $res= $con->query($sql);
                $f = $con->query($sql)->fetch_assoc();
                ?>
                <span><?=$f["NUM"]?></span>
                <?php
                $res->close();
                $sql="SELECT COUNT(ID_MENSAJE) NUM FROM mensajes WHERE ID_ENVIADOR=$id";
                $res= $con->query($sql);
                $f = $con->query($sql)->fetch_assoc();
                ?>
                <span><?=$f["NUM"]?></span>
            </section>
        </div>
    </section>
    <?php
    ?>

</main>

<footer>
    <div>
        <p>Creado por Javier Calvo Porro</p>
        <table>
            <tr>
                <td><img id="github" src="../IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                <td><a href="https://github.com/Javier0703/APP-WebMart" target="_blank">Disponible en GitHub</a></td>
            </tr>
        </table>
    </div>
</footer>

</body>
<script src="../JS_APP/header.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="../JS_APP/stats.js"></script>
</html>