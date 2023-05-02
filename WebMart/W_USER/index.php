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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,-25"/>
    <title>WebMart</title>
    <link rel="stylesheet" href="../CSS/estilos.css">
</head>

<body>

<header>
    <a href="index.php"><img src="../IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>
    <nav>
        <section>
            <a href="estadísticas.php"><img src="../IMG/ICONS_NAV/estadisticas.png" alt="Estadisticas"><span>Estadísticas</span></a>
            <a href="usuarios.php"><img src="../IMG/ICONS_NAV/grupo.png" alt="Usuarios"><span>Usuarios</span></a>
            <a href="publicar.php"><img src="../IMG/ICONS_NAV/agregar.png" alt="Subir Producto"><span>Subir un Producto</span></a>
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
                <section><a href=""><span class="material-symbols-outlined">shopping_cart</span><p>Compras</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">sell</span><p>Ventas</p></a></section>
                <section><a href=""><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>
    </nav>
</header>

<main class="mIndex">

    <section class="sIndex1">
        <h1>Compra y vende productos de todo tipo</h1>
        <h3>¡Sin necesidad de moverte!</h3>
    </section>

    <section class="sIndex2">
        <h1>¿Qué es lo que estás buscando?</h1>

        <form action="productos.php" method="GET">
            <div class="grid1"><img src="../IMG/LOGOS_ERRORES/lupa.png" alt="lupa"><input type="text" name="prod" placeholder="Estoy buscando..."></div>
            <div class="grid2" id="grid2">
                <section>
                    <span class="material-symbols-outlined">format_list_bulleted</span>
                    <p id="indexPtitle">Elige una Categoría</p>
                    <span class="material-symbols-outlined expandMore">expand_more</span>
                </section>
            </div>
            <div class="grid3"><button>Buscar</button></div>
            <section class="popUp">
                <div>
                    <section class="sec1popUp">
                        <h3>Categorías</h3>
                        <span class="material-symbols-outlined">close</span>
                    </section>
                    <?php
                    $con = conexUsu();
                    $sql = "SELECT COUNT(*) FROM categorias c JOIN subcategorias s using (id_cat)";
                    $st=$con->prepare($sql);
                    $st->execute();
                    $st->bind_result($filas);
                    $st->fetch();
                    $filasRest=$filas;
                    $st->close();
                    $sql="SELECT c.ID_CAT, c.NOMBRE, s.ID_SUB, s.NOMBRE FROM categorias c JOIN subcategorias s using (id_cat)";
                    $st=$con->prepare($sql);
                    $st->execute();
                    $st->bind_result($idCat,$nombreCat,$idSub,$nombreSub);
                    ?>
                    <section class="sec2popUp">
                    <?php
                    $st->fetch();
                    $filasRest--;
                    while ($filasRest>0){
                    ?>
                        <div><img src="../IMG/CATEGORIAS/<?=$idCat?>.png"><p><?=$nombreCat?></p><span class="material-symbols-outlined">chevron_right</span></div>
                        <?php
                    $cat=$idCat;
                    ?>
                        <aside>
                            <div class="typeAtr"><span class="material-symbols-outlined">chevron_left</span><p>Atrás</p></div>
                            <div class="typeCat" id='<?=$idCat?>'><p>Todo sobre <?=$nombreCat?></p></div>
                        <?php
                        while ($cat==$idCat){
                        ?>
                            <div id='<?=$idSub?>'><p><?=$nombreSub?></p></div></a>
                            <?php
                            $st->fetch();
                            $filasRest--;
                        }
                        ?>
                        </aside>
                    <?php
                    }
                    ?>
                    </section>
                    <?php
                    $st->close();
                    $con->close();
                    ?>
                </div>
            </section>
            <input id="indexHidden" type="hidden" name="" value="">
        </form>

    </section>

    <section class="sIndex3">

        <form action="productos.php" method="GET">
            <?php
            $con=conexUsu();
            $sql="SELECT ID_CAT,NOMBRE FROM categorias ORDER BY ID_CAT";
            $st=$con->prepare($sql);
            $st->execute();
            $st->bind_result($id,$nombre);
            while ($st->fetch()){
            ?>
                <a href="productos.php?id_cat=<?=$id?>"><div><img src="../IMG/CATEGORIAS/<?=$id?>.png" alt="Categoría"><p><?=$nombre?></p></div></a>
            <?php
            }
            ?>
        </form>
    </section>

    <section class="sIndex4">

        <h1>Se acerca el verano... ¿No sabes que hacer?</h1>
        <h3>¡Te ayudamos! Mira estos planazos que puedes ver</h3>

        <form action="productos.php" method="GET">
            <?php
            $con=conexUsu();
            $sql="SELECT ID_SUB FROM subcategorias WHERE NOMBRE LIKE '%Piscinas hinchables%' OR NOMBRE LIKE '%Alquiler vacacional%' OR  NOMBRE LIKE'%Surf%' OR NOMBRE LIKE '%Pesca' ORDER BY 1";
            $st=$con->prepare($sql);
            $st->execute();
            $st->bind_result($id);
            $st->fetch();
            ?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div class="grid1">
                    <aside>
                    <p>Si tienes jardín este producto te encantará...</p>
                    <h3>Piscinas hinchables</h3>
                    </aside>
                    <aside>
                        <img src="../IMG/IMAGENES_GIFS/piscina.png" alt="Piscina">
                    </aside>
                </div>
            </a>
            <?php
            $st->fetch()?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div class="grid2">
                    <aside>
                        <p>Un buen plan con tus amigos...</p>
                        <h3>Alquileres vacacionales</h3>
                    </aside>
                    <aside>
                        <img src="../IMG/IMAGENES_GIFS/alquiler.png" alt="Alquiler">
                    </aside>
                </div>
            </a>
            <?php
            $st->fetch()?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div class="grid3">
                    <aside>
                        <p>¿Te gusta el agua y el deporte?</p>
                        <h3>Actividades de surfing</h3>
                    </aside>
                    <aside>
                        <img id="img3" src="../IMG/IMAGENES_GIFS/surf.png" alt="Surf">
                    </aside>
                </div>
            </a>
            <?php
            $st->fetch()?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div class="grid4">
                    <aside>
                        <p>Otra actividad acuática</p>
                        <h3>Pesca</h3>
                    </aside>
                    <aside>
                        <img id="img3" src="../IMG/IMAGENES_GIFS/pesca.png" alt="Pesca">
                    </aside>
                </div>
            </a>
            <?php
            $st->close();
            $con->close();?>
        </form>
    </section>

    <section class="sIndex5">
        <h1>Regalos y productos top</h1>
        <form action="productos.php" method="GET">
            <?php
            $con=conexUsu();
            $sql="SELECT ID_SUB, NOMBRE FROM subcategorias WHERE NOMBRE LIKE '%Cámaras%' OR NOMBRE LIKE '%Drones%' OR  NOMBRE='Libros' OR NOMBRE LIKE '%Monitores%' ORDER BY 2";
            $st=$con->prepare($sql);
            $st->execute();
            $st->bind_result($id,$nombre);
            $st->fetch();
            ?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div>
                    <aside><p><?=$nombre?></p></aside>
                    <aside><img src="../IMG/IMAGENES_GIFS/camara.png" alt="Camara"></aside>
                </div>
            </a>
            <?php
            $st->fetch();
            ?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div>
                    <aside><p><?=$nombre?></p></aside>
                    <aside><img src="../IMG/IMAGENES_GIFS/dron.png" alt="Dron"></aside>
                </div>
            </a>
            <?php
            $st->fetch();
            ?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div>
                    <aside><p><?=$nombre?></p></aside>
                    <aside><img src="../IMG/IMAGENES_GIFS/libro.png" alt="Libro"></aside>
                </div>
            </a>
            <?php
            $st->fetch();
            ?>
            <a href="productos.php?id_sub=<?=$id?>">
                <div>
                    <aside><p><?=$nombre?></p></aside>
                    <aside><img src="../IMG/IMAGENES_GIFS/monitor.png" alt="Monitor"></aside>
                </div>
            </a>
            <?php
            $st->close();
            $con->close();?>
        </form>
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
<script src="../JS_APP/app.js"></script>
</html>