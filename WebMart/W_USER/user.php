<?php
include("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_GET["id_usu"])){
    if (strlen($_GET["id_usu"])===0){
        header("Location: usuarios.php");
    }
    if (!is_numeric($_GET["id_usu"]) || $_GET["id_usu"]<=0){
        header("Location: usuarios.php");
    }
}

else{
    header("Location: usuarios.php ");
}

if ((isset($_COOKIE["usu"]) && isset($_COOKIE["pass"])) || (isset($_SESSION["usu"]) && isset($_SESSION["pass"]))) {

    if (isset($_COOKIE["usu"]) && $_COOKIE["pass"]) {
        $usu = base64_decode($_COOKIE["usu"]);
        $pass = base64_decode($_COOKIE["pass"]);
    }

    if (isset($_SESSION["usu"]) && $_SESSION["pass"]) {
        $usu = $_SESSION["usu"];
        $pass = $_SESSION["pass"];
    }

    if (conexUsu() == 0) {
        $cod = conexUsu();
        setcookie("error", "Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location: ../error.php");
    } else {

        try {
            $con = conexUsu();
            $sql = "SELECT ID_USU, ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st = $con->prepare($sql);
            $st->bind_param("ss", $usu, $pass);
            $st->execute();
            $st->bind_result($idDB,$estado, $rol);

            if ($st->fetch()) {

                if ($rol == 1) {
                    $st->close();
                    $con->close();
                    header("Location: ../W_ADMIN/index.php");
                }

                if ($estado == 0) {
                    //Tu cuenta has sido bloqueada
                    $msg = "Tu cuenta ha sido bloqueada";
                    setcookie("msg", $msg);
                    $st->close();
                    $con->close();
                    header("Location: ../index.php");
                }

                $st->close();
                $con->close();
                define("USU", $usu);
                define("PASS", $pass);
                define("IDUSU", $idDB);

            }
        } catch (mysqli_sql_exception $e) {
            $cod = $e->getCode();
            $msgError = $e->getMessage();
            setcookie("error", "Error $cod, $msgError");
            header("Location: ../error.php");
        }
    }
}

else {
    header("Location:../cierre.php");
}

if ($idDB==$_GET["id_usu"]){
    header("Location: perfil/perfil.php");
}

$con=conexUsu();
$iD=$_GET["id_usu"];
$busqueda = "SELECT ID_USU FROM usuarios WHERE ID_USU=$iD";
$res = $con->query($busqueda);
$f = $res->fetch_assoc();
$con->close();
if (!$f){
    header("Location: usuarios.php");
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
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
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">rate_review</span><p>Mis Opiniones</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">edit_note</span><p>Opiniones</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">bookmarks</span><p>Reservas</p></a></section>
                <section><a href="perfil/mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>

    </nav>

</header>

<main class="mUserP">
    <?php
    $sql= "select USUARIO,ICONO,NOMBRE,APELLIDO1,APELLIDO2, DESCRIPCION, DIRECCION from usuarios WHERE ID_USU=?";
    $st = $con->prepare($sql);
    $st->bind_param("s", $_GET["id_usu"]);
    $st->execute();
    $st->bind_result($user,$icono, $nombre, $apellido1, $apellido2, $desc, $direc);
    $fila = $st->fetch();
    if (!$fila){
        ?>
        <div class="noResult">
            <p>Vaya... ¿Seguro has escrito bien el usuario? :(</p>
            <img src="../IMG/LOGOS_ERRORES/noFound.jpg" alt="noFound">
        </div>
    <?php
        $st->close();
        $con->close();
    }

    else{
        ?>
        <section class="sUserP1">
            <aside class="perfil">
                <div>
                    <div>
                        <img src="data:image/jpg;base64,<?=base64_encode($icono)?>">
                    </div>
                    <div>
                        <p><?=$user?></p>
                    </div>
                </div>
             </aside>
            <aside class="info">
                <?php
                    if ($nombre==null && $apellido1==null && $apellido2==null){
                        echo "<p style='color: gray'>Sin nombre</p>";
                    }
                    else{
                        echo "<p>$nombre $apellido1 $apellido2</p>";
                    }

                    if ($desc==null){
                        echo "<p style='color: gray'>Sin descripción</p>";
                    }
                    else{
                        echo "<p>$desc</p>";
                    }
                ?>

            </aside>
            <aside class="map">
                <div>
                    <span class="material-symbols-outlined">location_on</span>
                    <?php
                    if ($direc==null){
                        echo "<p style='color: gray'>Sin dirección</p>";
                    }
                    else{
                        echo "<p>$direc</p>";
                    }
                    ?>
                </div>
                <div id="map"></div>
            </aside>
        </section>
    <?php
    }
    ?>

    <section class="sUserP2">
        <ul>
            <li class="active">Artículos</li>
            <li>Valoraciones</li>
        </ul>

        <section class="active">
            <?php
            $idUsu= $_GET["id_usu"];
            $con = conexUsu();
            $sql= "SELECT p.ID_PROD ID_P, TITULO, ID_RESERVA R, PRECIO, FOTO FROM productos p JOIN fotos f USING (ID_PROD) WHERE ID_USU=$idUsu AND ID_COMPRADOR IS NULL GROUP BY (ID_PROD) ORDER BY FECHA_SUBIDA DESC";
            $res=$con->query($sql);
            if (!$fila = $res->fetch_assoc()){
            ?>
            <div class="noResult">
                <p>Vaya... parece que no tiene nada publicado... :(</p>
                <img src="../IMG/LOGOS_ERRORES/no_prods.jpg" alt="noFound">
            </div>
            <?php
            }
            else{
                ?>
            <section class="products">
                <?php
                while ($fila){
                    ?>
                    <a href="prod.php?id_prod=<?=$fila["ID_P"]?>" target="_blank">
                        <div style="background-image: url('data:image/jpg;base64,<?=base64_encode($fila["FOTO"])?>')"></div>
                        <section>
                            <p><?=strtoupper($fila["TITULO"])?></p>
                            <div>
                                <p><?=number_format($fila["PRECIO"], 0, '', '.')?> €</p>
                                <?php
                                if ($fila["R"] != null)echo "<p class='reserved'>Reservado</p>";
                                ?>
                            </div>
                        </section>
                    </a>
                    <?php
                    $fila= $res->fetch_assoc();
                }
                ?>
            </section>
            <?php
            }
            $st->close();
            ?>
        </section>

        <section>
            <?php
            $idUsu=$_GET["id_usu"];
            $sql = "select f.FOTO, p.titulo, u.USUARIO, o.VALORACION, o.MENSAJE from fotos f join productos p on f.ID_PROD = p.ID_PROD join opiniones o on p.ID_PROD = o.ID_PROD join usuarios u on o.ID_USU = u.ID_USU WHERE p.ID_USU=$idUsu group by p.ID_PROD";
            $res = $con->query($sql);
            if (!$fila = $res->fetch_assoc()){
                ?>
                <div class="noResult">
                    <p>Vaya... parece que nadie ha opinado todavía... :(</p>
                    <img src="../IMG/LOGOS_ERRORES/noOpinions.jpg" alt="noFound">
                </div>
                <?php
            }
            else{
                ?>
                <section class="valoration">
                    <?php
                        while ($fila){
                            ?>
                            <div>

                                <aside>
                                    <img src="data:image/jpg;base64,<?=base64_encode($fila["FOTO"])?>">
                                </aside>

                                <aside>
                                    <div>
                                        <p><?=$fila["USUARIO"]?></p>
                                        <p><?=$fila["VALORACION"]?>/5</p>
                                    </div>
                                    <p><?=$fila["MENSAJE"]?></p>
                                </aside>

                            </div>
                    <?php
                            $fila= $res->fetch_assoc();
                        }
                    }
                   ?>
                </section>
            <?php
                $idGet = $_GET["id_usu"];
                $idUsu = IDUSU;
                $sql="SELECT p.ID_PROD, p.TITULO, o.MENSAJE from productos p join usuarios u on p.ID_USU = u.ID_USU left outer join opiniones o using (ID_PROD) where p.ID_USU=$idGet AND p.ID_COMPRADOR=$idUsu AND o.MENSAJE is null";
                $res = $con->query($sql);
                $numFiles = $res->num_rows;
                if ($numFiles>0){
                    ?>
                    <form action="opinion.php" class="formOpinar">
                        <p>Artículos sin opinar de él:</p>
                        <select name="id_prod" id="id_prod">
                        <?php
                        $fila = $res->fetch_assoc();
                        while ($fila){
                            ?>
                            <option value="<?=$fila["ID_PROD"]?>"><?=$fila["TITULO"]?></option>
                            <?php
                            $fila = $res->fetch_assoc();
                        }
                        $con->close();
                        ?>
                        </select>
                        <button>Opinar</button>
                    </form>
                <?php
                }
            ?>
        </section>

    </section>

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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../JS_APP/user.js"></script>

</html>
