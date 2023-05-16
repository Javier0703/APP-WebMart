<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_GET["id_prod"]) && strlen(trim($_GET["id_prod"]))>0 ){

        if (!is_numeric($_GET["id_prod"])){
            setcookie("block","block");
            header("Location: block.php");
        }

        if ($_GET["id_prod"]<=0){
            header("Location: productos.php");
        }
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
            $sql="SELECT ID_USU,ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$usu,$pass);
            $st->execute();
            $st->bind_result($id_usu,$estado,$rol);

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
                define("IDUSU",$id_usu);

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
                <section><a href="perfil/perfil.php"><span class="material-symbols-outlined">person</span><p>Perfil</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">favorite</span><p>Favoritos</p></a></section>
                <section><a href="perfil/compras.php"><span class="material-symbols-outlined">shopping_cart</span><p>Compras</p></a></section>
                <section><a href="perfil/productos.php"><span class="material-symbols-outlined">sell</span><p>Productos</p></a></section>
                <section><a href="perfil/mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>
    </nav>
</header>

<main class="mPSelected">
    <?php
    $con=conexUsu();
    $sql="SELECT ID_PROD FROM productos WHERE ID_PROD=?";
    $st= $con->prepare($sql);
    $st->bind_param("i", $_GET["id_prod"]);
    $st->execute();
    $st->bind_result($id_p);

    if (!$st->fetch()){
        ?>
        <div class="noResult noProduct">
            <p>Vaya... no hemos podido encontrar el producto... :(</p>
            <img src="../IMG/LOGOS_ERRORES/noResultProduct.jpg" alt="noProduct:(">
        </div>
    <?php
    $st->close();
    $con->close();
    }

    else {

        $st->close();
        $prod= $_GET["id_prod"];
        $sql = "SELECT c.NOMBRE N_CAT, s.NOMBRE N_SUB ,p.TITULO, p.DESCRIPCION, DATE_FORMAT(FECHA_SUBIDA, '%d/%m/%Y a las %H:%i') FECHA, p.PESO, p.PRECIO, p.FECHA_SUBIDA, p.ID_RESERVA, p.ID_COMPRADOR, p.ID_USU, u.USUARIO, u.ICONO, u.DIRECCION FROM productos p join usuarios u on u.ID_USU = p.ID_USU join subcategorias s on p.ID_SUB = s.ID_SUB join categorias c on s.ID_CAT = c.ID_CAT WHERE p.ID_PROD=$prod";
        $fila = $con->query($sql)->fetch_assoc();
        //Desde aqui hacer los sections
        $idSes=IDUSU;

        if ($fila["ID_USU"]==$idSes){
            if ($fila["ID_COMPRADOR"]==null){
                ?>
                <section class="miProducto">
                    <p>Este producto es tuyo, lo que no podrás comprarlo ni reservarlo, pero sí podrás editarlo.</p>
                </section>
                <?php
            }
        }

        if ($fila["ID_COMPRADOR"]!=null){
            ?>
            <section class="comprado">
                <?php
                if ($fila["ID_COMPRADOR"]!=$idSes && $fila["ID_USU"]==$idSes){
                    echo "<p>Este producto ha sido vendido por tí, lo que no podrás editarlo</p>";
                }
                else if ($fila["ID_COMPRADOR"]==$idSes && $fila["ID_USU"]!=$idSes){
                    echo "<p>Este producto ha sido comprado por tí</p>";
                }
                else{
                    echo "<p>Oops! Este producto ya ha sido comprado por otro usuario, has llegado tarde :(</p>";
                }
                ?>
            </section>
        <?php
        }
        ?>

        <section class="gridFyP">

            <div class="profile">
                <a href="user.php?id_usu=<?=$fila["ID_USU"]?>">
                    <img src="data:image/jpg;base64, <?=base64_encode($fila["ICONO"]);?>"/>
                    <div>
                        <p><?=$fila["USUARIO"]?></p>
                        <?php
                        $idCount=$fila["ID_USU"];
                        $cProds="SELECT COUNT(ID_PROD) N FROM productos WHERE ID_USU=$idCount";
                        $sum = $con->query($cProds)->fetch_assoc();
                        ?>
                        <p><?=$sum["N"]?> productos</p>
                        <?php
                        $con->query($cProds)->close();
                        ?>
                    </div>
                </a>

                <div>
                    <span class="material-symbols-outlined">location_on</span>
                    <p><?=$fila["DIRECCION"]?></p>
                </div>





                <p class="abajo">Artículo subido el <?=$fila["FECHA"]?></p>
            </div>

            <div class="image">
                <?php
                $con2=conexUsu();
                $sql2="SELECT FOTO FROM fotos WHERE ID_PROD=$prod";
                $res2= $con2->query($sql2);
                $fila2 = $res2->fetch_assoc();
                while ($fila2){
                    ?>
                    <img src="data:image/jpg;base64, <?=base64_encode($fila2["FOTO"]);?>" />
                    <?php
                    $fila2= $res2->fetch_assoc();
                }
                $res2->close();
                $con2->close();
                ?>

                <div id="left">
                    <span class="material-symbols-outlined">arrow_back_ios</span>
                </div>

                <div id="right">
                    <span class="material-symbols-outlined">arrow_forward_ios</span>
                </div>

            </div>

        </section>




    <?php
    }
    ?>
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
<script src="../JS_APP/pProd.js"></script>
</html>
