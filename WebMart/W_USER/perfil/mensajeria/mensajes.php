<?php
include ("../../../conexDB.php");
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
        header("Location: ../../../error.php");
    }

    else{

        try {
            $con=conexUsu();
            $sql="SELECT ID_USU,ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($id_usu,$estado,$rol);

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
                    header("Location: ../../../index.php");
                }

                $st->close();
                $con->close();
                define("USU",$usu);
                define("PASS",$pass);
                define("IDUSU",$id_usu);

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
    header("Location: ../../../cierre.php");
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="../../../IMG/LOGOS_ERRORES/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,-25"/>
    <title>WebMart</title>
    <link rel="stylesheet" href="../../../CSS/estilos.css">
</head>

<body>

<header>
    <a href="../../index.php"><img src="../../../IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>
    <nav>
        <section>
            <a href="../../estadísticas.php"><img src="../../../IMG/ICONS_NAV/estadisticas.png" alt="Estadisticas"><span>Estadísticas</span></a>
            <a href="../../usuarios.php"><img src="../../../IMG/ICONS_NAV/grupo.png" alt="Usuarios"><span>Usuarios</span></a>
            <a href="../../publicar.php"><img src="../../../IMG/ICONS_NAV/agregar.png" alt="Subir Producto"><span>Subir un Producto</span></a>
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
                <section><a href="../perfil.php"><span class="material-symbols-outlined">person</span><p>Perfil</p></a></section>
                <section><a href="../favoritos.php"><span class="material-symbols-outlined">favorite</span><p>Favoritos</p></a></section>
                <section><a href="../compras.php"><span class="material-symbols-outlined">shopping_cart</span><p>Compras</p></a></section>
                <section><a href="../productos.php"><span class="material-symbols-outlined">sell</span><p>Productos</p></a></section>
                <section><a href="mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../../../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>
    </nav>
</header>

<main class="mPerfil">
    <section>
        <aside id="a1">
            <a href="../perfil.php">
                <?php
                $usu=USU;
                $con=conexUsu();
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
                        ?>
                    </p>
                </div>
            </a>

            <section>

                <a href="../productos.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/prod.png" alt="Prod">
                    <div>
                        <p>Productos</p>
                    </div>
                </a>

                <a href="../compras.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/compras.png" alt="Compras">
                    <div>
                        <p>Compras</p>
                    </div>
                </a>

                <a href="../favoritos.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/favoritos.png" alt="Favoritos">
                    <div>
                        <p>Favoritos</p>
                    </div>
                </a>

                <a href="../misOpiniones.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/misOpiniones.png" alt="Favoritos">
                    <div>
                        <p>Mis Opiniones</p>
                    </div>
                </a>

                <a href="../opiniones.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/OpinionesRecibidas.png" alt="Favoritos">
                    <div>
                        <p>Opiniones</p>
                    </div>
                </a>

                <a href="../reservas.php">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/Reservas.png" alt="Favoritos">
                    <div>
                        <p>Reservas</p>
                    </div>
                </a>

                <a href="mensajes.php" class="aSelected">
                    <img src="../../../IMG/ICONS_NAV/ICONOS_ASIDE/mensajes.png" alt="Mensajes">
                    <div>
                        <p>Mensajes</p>
                    </div>
                </a>

            </section>
        </aside>

        <aside id="a2">

            <section id="msj">

                <section class="titulo">
                    <p>Aquí verás todos tus chats</p>
                </section>

                <section class="sectionMSG" id="chatsEnv">
                    <h3>En búsqueda de compras...</h3>
                    <?php
                    $con=conexUsu();
                    $idSes = IDUSU;
                    $sql = "SELECT f.FOTO, p.TITULO, c.ID_CHAT, c.ID_PROD, c.ID_USU, c.ULTIMA_CONEX_USU, m.ID_MENSAJE, m.MENSAJE, m.ID_ENVIADOR, m.HORA
                    FROM chats c LEFT JOIN mensajes m ON c.ID_CHAT = m.ID_CHAT JOIN productos p ON c.ID_PROD = p.ID_PROD JOIN fotos f ON p.ID_PROD = f.ID_PROD
                    WHERE c.ID_USU = $idSes AND (m.ID_MENSAJE IN (SELECT MAX(ID_MENSAJE) from mensajes WHERE m.ID_CHAT=c.ID_CHAT GROUP BY ID_CHAT) OR m.ID_CHAT IS NULL)
                    GROUP BY c.ID_CHAT
                    ORDER BY m.HORA desc";
                    $res= $con->query($sql);
                    $nR = $res->num_rows;

                    if ($nR > 0){
                        $fila = $res->fetch_assoc();

                        while ($fila){
                            ?>
                            <a href="chat.php?id_chat=<?=$fila["ID_CHAT"]?>">

                                <div style="background-image: url('data:image/jpg;base64,<?=base64_encode($fila["FOTO"])?>')">

                                </div>

                                <div>

                                   <section>

                                       <p>
                                           <?=$fila["TITULO"]?>
                                       </p>
                                       <span>
                                           <?php

                                           date_default_timezone_set('Europe/Madrid');
                                           $fechaHoy = date('Y-m-d');
                                           $fechaAyer = date('Y-m-d', strtotime('-1 day'));

                                           $fechaDB = strtotime($fila["HORA"]);
                                           $fechaF= date('Y-m-d', $fechaDB);

                                           if ($fechaF == $fechaHoy){
                                               $hora = date('H', strtotime($fila["HORA"]));
                                               $minutos = date('i', strtotime($fila["HORA"]));
                                               echo $hora.":".$minutos;
                                           }

                                           elseif ($fechaF == $fechaAyer){
                                               echo "Ayer";
                                           }

                                           else{
                                               date('Y-m-d', $fila["HORA"]);
                                           }

                                           ?>
                                       </span>

                                   </section>

                                    <?php
                                    if ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_USU"]<=$fila["HORA"]){
                                       ?>
                                        <p style="font-weight: bold"><?=$fila["MENSAJE"]?></p>
                                    <?php
                                    }
                                    elseif ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_USU"]>$fila["HORA"]){
                                        ?>
                                        <p><?=$fila["MENSAJE"]?></p>
                                        <?php
                                    }
                                    elseif ($fila["ID_ENVIADOR"]==$idSes){
                                        ?>
                                        <p>Tú: <?=$fila["MENSAJE"]?></p>
                                        <?php
                                    }
                                    ?>
                                </div>

                            </a>
                            <?php
                            $fila = $res->fetch_assoc();
                        }
                        $res->close();
                    }

                    else{
                        ?>
                        <div class="noResult">
                            <p>Vaya... parece no has contactado con nadie ... :( <a href="../../productos.php">¡Busca alguno!</a> </p>
                            <img src="../../../IMG/LOGOS_ERRORES/noChat.jpg" alt="noFound">
                        </div>
                    <?php
                    }
                    ?>
                </section>

                <section class="sectionMSG" id="chatsRec">
                    <h3>En búsqueda de ventas...</h3>
                    <?php
                    $con=conexUsu();
                    $idSes = IDUSU;
                    $sql = "SELECT f.FOTO, p.TITULO, c.ID_CHAT, c.ID_PROD, c.ID_USU, c.ULTIMA_CONEX_USU, m.ID_MENSAJE, m.MENSAJE, m.ID_ENVIADOR, m.HORA
                    FROM chats c LEFT JOIN mensajes m ON c.ID_CHAT = m.ID_CHAT JOIN productos p ON c.ID_PROD = p.ID_PROD JOIN fotos f ON p.ID_PROD = f.ID_PROD
                    WHERE (m.ID_MENSAJE IN (SELECT MAX(ID_MENSAJE) from mensajes WHERE m.ID_CHAT=c.ID_CHAT GROUP BY ID_CHAT) OR m.ID_CHAT IS NULL)
                    AND c.ID_PROD IN (SELECT ID_PROD from productos p WHERE p.ID_USU=$idSes) GROUP BY c.ID_CHAT ORDER BY m.HORA desc";
                    $res= $con->query($sql);
                    $nR = $res->num_rows;

                    if ($nR > 0){
                        $fila = $res->fetch_assoc();

                        while ($fila){
                            ?>
                            <a href="chat.php?id_chat=<?=$fila["ID_CHAT"]?>">

                                <div style="background-image: url('data:image/jpg;base64,<?=base64_encode($fila["FOTO"])?>')">

                                </div>

                                <div>

                                    <section>

                                        <p>
                                            <?=$fila["TITULO"]?>
                                        </p>
                                        <span>
                                           <?php
                                           date_default_timezone_set('Europe/Madrid');
                                           $fechaHoy = date('Y-m-d');
                                           $fechaAyer = date('Y-m-d', strtotime('-1 day'));

                                           $fechaDB = strtotime($fila["HORA"]);
                                           $fechaF= date('Y-m-d', $fechaDB);

                                           if ($fechaF == $fechaHoy){
                                               $hora = date('H', strtotime($fila["HORA"]));
                                               $minutos = date('i', strtotime($fila["HORA"]));
                                               echo $hora.":".$minutos;
                                           }

                                           elseif ($fechaF == $fechaAyer){
                                               echo "Ayer";
                                           }

                                           else{
                                               date('Y-m-d', $fila["HORA"]);
                                           }

                                           ?>
                                       </span>

                                    </section>

                                    <?php
                                    if ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_USU"]<=$fila["HORA"]){
                                        ?>
                                        <p style="font-weight: bold"><?=$fila["MENSAJE"]?></p>
                                        <?php
                                    }
                                    elseif ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_USU"]>$fila["HORA"]){
                                        ?>
                                        <p><?=$fila["MENSAJE"]?></p>
                                        <?php
                                    }
                                    elseif ($fila["ID_ENVIADOR"]==$idSes){
                                        ?>
                                        <p>Tú: <?=$fila["MENSAJE"]?></p>
                                        <?php
                                    }
                                    ?>

                                </div>

                            </a>
                            <?php
                            $fila = $res->fetch_assoc();
                        }
                        $res->close();

                    }

                    else{
                        ?>
                        <div class="noResult">
                            <p>Vaya... parece que nadie ha contactado con usted :(</p>
                            <img src="../../../IMG/LOGOS_ERRORES/noChat2.jpg" alt="noFound">
                        </div>
                        <?php
                    }
                    ?>
                </section>

            </section>

            <footer class="footerProfile">
                <div>
                    <p>Creado por Javier Calvo Porro</p>
                    <table>
                        <tr>
                            <td><img id="github" src="../../../IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                            <td>Github</td>
                        </tr>
                    </table>
                </div>
            </footer>
        </aside>

    </section>
</main>

</body>
<script src="../../../JS_APP/header.js"></script>
</html>