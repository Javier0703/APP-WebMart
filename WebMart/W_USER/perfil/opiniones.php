<?php

include ("../../conexDB.php");
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
        header("Location: ../../error.php");
    }

    else{

        try {
            $con=conexUsu();
            $sql="SELECT ID_USU,ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($IDdb,$estado,$rol);

            if ($st->fetch()){

                if ($rol==1){
                    $st->close();
                    $con->close();
                    header("Location: ../../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    $st->close();
                    $con->close();
                    header("Location: ../../index.php");
                }

                $st->close();
                $con->close();
                define("USU",$usu);
                define("PASS",$pass);
                define("IDU",$IDdb);

            }

            else{
                header("Location: ../../cierre.php");
            }

        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location: ../../error.php");
        }
    }
}

else{
    header("Location: ../../cierre.php");
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../../IMG/LOGOS_ERRORES/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,-25"/>
    <title>WebMart</title>
    <link rel="stylesheet" href="../../CSS/estilos.css">
</head>

<body>

<header>

    <a id="logo" href="../index.php"><img src="../../IMG/LOGOS_ERRORES/logo.png" alt="Logo"></a>
    <a id="logoE" href="../index.php"><img src="../../IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>

    <nav>

        <section >

            <img id="fDesplig" src="../../IMG/LOGOS_ERRORES/despleg.png" alt="Logo">

            <section>
                <a href="../estadísticas.php"><img src="../../IMG/ICONS_NAV/estadisticas.png" alt="Estadisticas"><span>Estadísticas</span></a>
                <a href="../usuarios.php"><img src="../../IMG/ICONS_NAV/grupo.png" alt="Usuarios"><span>Usuarios</span></a>
                <a href="../publicar.php"><img src="../../IMG/ICONS_NAV/agregar.png" alt="Subir Producto"><span>Subir un Producto</span></a>
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
                <section><a href="perfil.php"><span class="material-symbols-outlined">person</span><p>Perfil</p></a></section>
                <section><a href="productos.php"><span class="material-symbols-outlined">sell</span><p>Productos</p></a></section>
                <section><a href="compras.php"><span class="material-symbols-outlined">shopping_cart</span><p>Compras</p></a></section>
                <section><a href="favoritos.php"><span class="material-symbols-outlined">favorite</span><p>Favoritos</p></a></section>
                <section><a href="misOpiniones.php"><span class="material-symbols-outlined">rate_review</span><p>Mis Opiniones</p></a></section>
                <section><a href="opiniones.php"><span class="material-symbols-outlined">edit_note</span><p>Opiniones</p></a></section>
                <section><a href="reservas.php"><span class="material-symbols-outlined">bookmarks</span><p>Reservas</p></a></section>
                <section><a href="mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>

    </nav>

</header>

<main class="mPerfil">
    <section>
        <aside id="a1">
            <a href="perfil.php">
                <?php
                $usu=USU;
                $sql="SELECT ID_USU,ICONO,USUARIO,NOMBRE,APELLIDO1 FROM usuarios WHERE USUARIO=?";
                $st=$con->prepare($sql);
                $st->bind_param("s",$usu);
                $st->execute();
                $st->bind_result($id_usuDB,$icono, $usuDB,$nDB,$aDB);
                $st->fetch();
                $id=$id_usuDB;
                $st->close();
                echo '<img src="data:image/jpg;base64,'.base64_encode($icono).'">';
                ?>
                <div>
                    <p>
                        <?php
                        if ($nDB===null){
                            echo $usuDB;
                        }
                        else{
                            echo ucfirst($usuDB)." ".strtoupper(substr($aDB, 0, 1));
                        }
                        ?>
                    </p>
                    <p>
                        <?php
                        $sql="SELECT COUNT(*) OP FROM opiniones join productos p USING (ID_PROD) WHERE p.ID_USU=?";
                        $st=$con->prepare($sql);
                        $st->bind_param("i",$id);
                        $st->execute();
                        $st->bind_result($opinion);
                        $st->fetch();
                        if ($opinion===1){
                            echo "1 opinión";
                        }
                        else if ($opinion>1){
                            echo $opinion. "opiniones";
                        }
                        else{
                            echo "Sin opiniones";
                        }
                        $st->close();
                        ?>
                    </p>
                </div>
            </a>

            <section>

                <a href="productos.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/prod.png" alt="Prod">
                    <div>
                        <p>Productos</p>
                    </div>
                </a>

                <a href="compras.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/compras.png" alt="Compras">
                    <div>
                        <p>Compras</p>
                    </div>
                </a>

                <a href="favoritos.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/favoritos.png" alt="Favoritos">
                    <div>
                        <p>Favoritos</p>
                    </div>
                </a>

                <a href="misOpiniones.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/misOpiniones.png" alt="Favoritos">
                    <div>
                        <p>Mis Opiniones</p>
                    </div>
                </a>

                <a href="opiniones.php" class="aSelected">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/OpinionesRecibidas.png" alt="Favoritos">
                    <div>
                        <p>Opiniones</p>
                    </div>
                </a>

                <a href="reservas.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/Reservas.png" alt="Favoritos">
                    <div>
                        <p>Reservas</p>
                    </div>
                </a>

                <a href="mensajeria/mensajes.php">
                    <img src="../../IMG/ICONS_NAV/ICONOS_ASIDE/mensajes.png" alt="Mensajes">
                    <div>
                        <p>Mensajes</p>
                    </div>
                </a>

            </section>
        </aside>

        <aside id="a2">

            <section class="sOpiniones">
                <section id="titulo">
                    <p>Estas son todas las opiniones que te han realizado</p>
                </section>

                <section id="opiniones">
                    <?php
                    try {
                        $iDdb=IDU;
                        $con=conexUsu();
                        $sql= "SELECT u.ICONO, u.USUARIO, p.TITULO, p.ID_PROD, o.VALORACION, o.MENSAJE FROM opiniones o JOIN productos p using (ID_PROD) JOIN usuarios u on u.ID_USU= o.ID_USU  WHERE p.ID_USU=$iDdb GROUP BY (p.ID_PROD)";
                        $res = $con->query($sql);

                        if (!$fila = $res->fetch_assoc()){
                            ?>
                            <div class="noResult">
                                <p>Vaya... parece que no te han opinado todavía... :(</p>
                                <img src="../../IMG/LOGOS_ERRORES/noOpinions.jpg" alt="noFound">
                            </div>
                            <?php
                        }

                        else{
                            while ($fila){
                                ?>

                                <a href="../prod.php?id_prod=<?=$fila["ID_PROD"]?>" id="opinionesRec">

                                    <p><?=$fila["TITULO"]?></p>
                                    <div>

                                        <section>
                                            <div>
                                                <img src="data:image/jpg;base64,<?=base64_encode($fila["ICONO"])?>">
                                                <p><?=$fila["USUARIO"]?></p>
                                            </div>
                                        </section>

                                        <section>
                                            <p> Val: <?=$fila["VALORACION"]?>/5</p>
                                            <p><?=$fila["MENSAJE"]?></p>
                                        </section>

                                    </div>

                                </a>

                                <?php
                                $fila= $res->fetch_assoc();
                            }
                        }
                    }

                    catch (mysqli_sql_exception $e){
                        $cod=$e ->getCode();
                        $msgError=$e->getMessage();
                        setcookie("error","Error $cod, $msgError");
                        header("Location: ../../error.php");
                    }
                    ?>
                </section>

            </section>

            <footer>
                <div>
                    <p>Creado por Javier Calvo Porro</p>
                    <table>
                        <tr>
                            <td><img id="github" src="../../IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                            <td><a href="https://github.com/Javier0703/APP-WebMart" target="_blank">Disponible en GitHub</a></td>
                        </tr>
                    </table>
                </div>
            </footer>

        </aside>

    </section>

</main>

</body>
<script src="../../JS_APP/header.js"></script>


</html>