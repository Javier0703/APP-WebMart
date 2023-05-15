<?php
include("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_GET["id_prod"])){
    if (strlen($_GET["id_prod"])===0){
        header("Location: usuarios.php");
    }
    if (!is_numeric($_GET["id_prod"]) || $_GET["id_prod"]<=0){
        header("Location: usuarios.php");
    }
}

else{
    header("Location: usuarios.php");
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
        header("Location:error.php");
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
            header("Location:error.php");
        }
    }
}

else {
    header("Location:../cierre.php");
}

$prod = $_GET["id_prod"];
$idComp=IDUSU;
$con= conexUsu();
$sql="SELECT ID_PROD, ID_COMPRADOR from productos WHERE ID_PROD=? AND ID_COMPRADOR=?";
$st = $con->prepare($sql);
$st->bind_param("ii", $prod, $idComp);
$st->execute();
$st->bind_result($idProd,$id_Comp);

if (!$fila = $st->fetch()){
    $st->close();
    $con->close();
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
            $st->close();
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

<main class="mOpinion">

    <section class="titulo">
        <p>Edita o añade una opinión sobre un producto comprado</p>
    </section>

    <section class="producto">
        <?php
        $con=conexUsu();
        $sql="SELECT TITULO, FOTO FROM productos JOIN FOTOS USING (ID_PROD) WHERE ID_PROD=$prod GROUP BY ID_PROD";
        $res= $con->query($sql);
        $fila = $res->fetch_assoc();
        echo '<div><img src="data:image/jpg;base64,'.base64_encode($fila["FOTO"]).'"></div>';
        echo "<p>".strtoupper($fila["TITULO"])."</p>";
        ?>
    </section>

    <section class="info">
        <form action="modifOpinion.php" method="POST">
            <?php
            $sql="SELECT p.ID_PROD, VALORACION, MENSAJE  from productos p left outer join opiniones o using (ID_PROD) where p.ID_PROD=$prod";
            $res = $con->query($sql);
            $fila = $res->fetch_assoc();
            ?>
            <label for="val">Valoración</label><br>
            <input id="val" type="text" name="valoracion" min="1" max="5" value="<?=$fila["VALORACION"]?>" placeholder="Del 1 al 5" required><br><br>
            <label for="desc">Descripcion</label><br>
            <textarea name="descripcion" id="desc" rows="6" placeholder="Se bueno..." required maxlength="400"><?=$fila["MENSAJE"]?></textarea>
            <input type="hidden" name="id_prod" value="<?=$fila["ID_PROD"]?>">
            <div>
                <button>Guardad</button>
            </div>
        </form>

        <form action="eliminarOpinion.php" method="POST" class="drop">
            <input type="hidden" name="id_prod" value="<?=$fila["ID_PROD"]?>">
            <button class="delete">Eliminar</button>
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
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>
<script src="../JS_APP/user.js"></script>

</html>
