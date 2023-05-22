<?php
include("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

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
            $sql = "SELECT ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st = $con->prepare($sql);
            $st->bind_param("ss", $usu, $pass);
            $st->execute();
            $st->bind_result($estado, $rol);

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

if (isset($_GET["usu"])){
    $pregName = preg_match('/^[a-zA-Z0-9_ñÑ]+$/', $_GET["usu"]);

    if (strlen($_GET["usu"])===0){
        //Esta bien
    }

    else if (strlen($_GET["usu"])>0 && !$pregName){
        setcookie("block","block");
        header("Location: block.php");
        exit;
    }
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
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">rate_review</span><p>Mis Opiniones</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">edit_note</span><p>Opiniones</p></a></section>
                <section><a href="perfil/favoritos.php"><span class="material-symbols-outlined">bookmarks</span><p>Reservas</p></a></section>
                <section><a href="perfil/mensajeria/mensajes.php"><span class="material-symbols-outlined">chat</span><p>Mensajes</p></a></section>
                <section><a href="../cierre.php"><span class="material-symbols-outlined">logout</span><p>Cerrar Sesión</p></a></section>
            </div>
        </div>

    </nav>

</header>

<main class="mUser">
    <form action="usuarios.php" method="GET">
        <p> Encuentra el perfil que estes buscando</p>

        <section>
            <div class="flex">
                <img src="../IMG/LOGOS_ERRORES/lupa.png" alt="Lupa">
                <input type="text" name="usu" placeholder="Estoy buscando..." value="<?php if (isset($_GET["usu"]) && strlen(trim($_GET["usu"]))>0){echo $_GET["usu"];}?>">
                <img src="../IMG/LOGOS_ERRORES/x.png" alt="Lupa"<?php if (isset($_GET["usu"]) && strlen(trim($_GET["usu"]))>0){echo 'style="opacity: 1"';}?>>
                </div>
            <button>Buscar</button>
            <p></p>
        </section>
    </form>

        <?php
        $usu=USU;
        if (isset($_GET["usu"])){
            $con=conexUsu();

            if (strlen(trim($_GET["usu"]))>0){
                $str= $_GET["usu"];
                $char = str_split($str);
                $regexp = implode(".*", $char);
                $sql="SELECT ID_USU, ICONO, USUARIO, count(TITULO) PRODS FROM usuarios u left join productos p using (id_usu) where USUARIO REGEXP '$regexp' and USUARIO!='$usu' group by USUARIO";
            }
            else{
                $sql="SELECT ID_USU, ICONO, USUARIO, count(TITULO) PRODS FROM usuarios u left join productos p using (id_usu) where USUARIO!='$usu' group by USUARIO";
            }
            $res= $con->query($sql);
            $fila= $res->fetch_assoc();

            if (!$fila){
                ?>
                <div class="noResult">
                    <p>Vaya... ¿Seguro has escrito bien el usuario? :(</p>
                    <img src="../IMG/LOGOS_ERRORES/noFound.jpg" alt="noFound">
                </div>
        <?php
            }
            ?>

            <section class="users">
            <?php
            while ($fila){
                ?>
                <a href="user.php?id_usu=<?=$fila["ID_USU"]?>">
                    <?php echo '<img src="data:image/jpg;base64,'.base64_encode($fila["ICONO"]).'">'; ?>
                    <div>
                        <p><?=$fila["USUARIO"]?></p>
                        <p><?=$fila["PRODS"]?> artículos</p>
                    </div>
                </a>
                <?php
                $fila= $res->fetch_assoc();
            }
            ?>
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
                <td><a href="https://github.com/Javier0703/APP-WebMart" target="_blank">Disponible en GitHub</a></td>
            </tr>
        </table>
    </div>
</footer>

</body>
<script src="../JS_APP/header.js"></script>
<script src="../JS_APP/usuarios.js"></script>

</html>
