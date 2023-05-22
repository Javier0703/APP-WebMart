<?php

if (isset($_COOKIE["correo"])) {
    $cookie=$_COOKIE["correo"];
    setcookie("correo",false);
}


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
            $sql="SELECT ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($estado,$rol);

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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
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
                <section><a href="favoritos.php"><span class="material-symbols-outlined">rate_review</span><p>Mis Opiniones</p></a></section>
                <section><a href="favoritos.php"><span class="material-symbols-outlined">edit_note</span><p>Opiniones</p></a></section>
                <section><a href="favoritos.php"><span class="material-symbols-outlined">bookmarks</span><p>Reservas</p></a></section>
                <section><a href="mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>

    </nav>

</header>

<main class="mPerfil">
    <section>
        <aside id="a1">
            <a href="perfil.php" class="aSelected">
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
                            echo $opinion. " opiniones";
                        }
                        else{
                            echo " Sin opiniones";
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

                <a href="opiniones.php">
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

            <form action="saveProfile.php " method="POST" enctype="multipart/form-data">

                <section class="sAPerfil1">
                    <h3>Icono del perfil</h3>
                        <div>
                                <?php
                                $usu=USU;
                                $sql="SELECT ICONO FROM usuarios WHERE USUARIO=?";
                                $st=$con->prepare($sql);
                                $st->bind_param("s",$usu);
                                $st->execute();
                                $st->bind_result($icono);
                                $st->fetch();
                                echo '<div id="vista-previa"><img src="data:image/jpg;base64,'.base64_encode($icono).'"></div>';
                                $st->close();
                                ?>
                            <input type="file" name="icono" accept="image/jpeg" id="file" onchange="mostrarImagen()">
                            <label for="file"><span class="material-symbols-outlined">add_photo_alternate</span><p>Cambiar icono</p></label>
                        </div>
                    <p>El formato debe de ser jpg y es recomendable que sea un icono (misma altura y anchura) para evitar deformaciones</p>
                </section>

                <section class="sAPerfil2">
                    <h3>Información pública</h3>
                    <section>
                        <div>
                            <?php
                            $usu=USU;
                            $sql="SELECT NOMBRE, APELLIDO1, APELLIDO2, DESCRIPCION, CORREO, DIRECCION FROM usuarios WHERE USUARIO=?";
                            $st=$con->prepare($sql);
                            $st->bind_param("s",$usu);
                            $st->execute();
                            $st->bind_result($n, $ap1, $ap2, $desc, $correo, $dir);
                            $st->fetch();
                            ?>
                            <label for="nombre">Nombre</label><br>
                            <input type="text" id="nombre" name="nombre" value="<?=$n?>" maxlength="30" placeholder="Nombre">
                        </div>
                        <div>
                            <label for="ape1">Primer Apellido</label><br>
                            <input type="text" id="ape1" name="ape1" value="<?=$ap1?>" placeholder="Primer apellido" maxlength="30">
                        </div>
                        <div>
                            <label for="ape2">Segundo Apellido</label><br>
                            <input type="text" id="ape2" name="ape2" value="<?=$ap2?>" placeholder="Segundo apellido" maxlength="30">
                        </div>
                        <div>
                            <label for="correo">Correo</label><br>
                            <div><input type="text" id="correo" name="correo" value="<?=$correo?>" placeholder="Correo" maxlength="40"></div>
                        </div>
                    </section>
                    <p id="error">
                        <?php
                        if ($cookie !== ''){
                            echo $cookie;
                            $cookie='';
                        }
                        ?>
                    </p>
                    <div>
                        <section><label for="descripcion">Descripción</label><span>0</span><p>/400</p></section>
                        <div><textarea name="desc" id="descripcion" rows="6" placeholder="Una descripción para saber un poco quien eres" maxlength="400"><?=$desc?></textarea></div>
                    </div>
                </section>

                <section class="sAPerfil3">
                    <h3>Dirección</h3>
                    <section>
                        <input id="direccion" type="text" name="direccion" value="<?=$dir?>" placeholder="Introduce tu dirección" maxlength="60">
                        <button id="comprobar">Comprobar</button>
                    </section>
                    
                    <div id="map">

                    </div>

                    <button id="guardar">Guardar</button>

                </section>
                <?php
                $st->close();
                ?>
            </form>

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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../../JS_APP/PERFIL/map.js"></script>


<script>
    function mostrarImagen() {
        let input = document.getElementById('file');
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                let vistaPrevia = document.getElementById('vista-previa');
                let imagen = new Image();
                imagen.src = e.target.result;
                vistaPrevia.innerHTML = '';
                vistaPrevia.appendChild(imagen);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

</html>

