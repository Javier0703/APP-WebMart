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

<main class="mPublic">

    <form action="subirProd.php" method="POST" enctype="multipart/form-data">

        <section class="sPublic1">
            <h3>¿Qué es lo que deseas subir?</h3>
            <p>En WebMart hay espacio para todo</p>

            <section>
                <div class="grid1">
                    <p>Elige la Categoría</p>
                </div>

                <div class="grid3">
                    <p>Categoría</p>
                    <span class="material-symbols-outlined">expand_more</span>
                </div>

                <div class="grid2">
                    <p>Elige la Subcategoría</p>
                </div>

                <div class="grid4 blocked">
                        <p>Subcategorías</p>
                        <span class="material-symbols-outlined">expand_more</span>
                </div>
            </section>

            <div class="pUp g3pU">
                <div>
                    <section>
                        <h4>Categorías</h4>
                        <span class="material-symbols-outlined">close</span>
                    </section>
                    <section>
                        <?php
                        $con=conexUsu();
                        $sql="SELECT ID_CAT, NOMBRE FROM categorias";
                        $st=$con->prepare($sql);
                        $st->execute();
                        $st->bind_result($ic, $n);
                        while ($st->fetch()){
                        ?>
                          <div class="category" id="<?=$ic?>"><img src="../IMG/CATEGORIAS/<?=$ic?>.png" alt="Icon"><?=$n?></div>
                        <?php
                        }
                        ?>
                    </section>
                </div>
            </div>

            <div class="pUp g4pU">
                <div>
                    <section>
                        <h4>Categorías</h4>
                        <span class="material-symbols-outlined">close</span>
                    </section>
                    <section>
                        <?php
                        $con=conexUsu();
                        $sql="SELECT ID_CAT, ID_SUB, NOMBRE FROM subcategorias";
                        $st=$con->prepare($sql);
                        $st->execute();
                        $st->bind_result($ic, $is, $n);
                        while ($st->fetch()){
                            ?>
                            <div class="subcategorias <?=$ic?>" id="<?=$is?>"><?=$n?></div>
                            <?php
                        }
                        ?>
                    </section>
                </div>
            </div>

            <input id="inputHidden" type="hidden" name="id_sub" value="">

            <p style="color: red; text-align: center"></p>
        </section>

        <section class="sPublic2">
            <h3>Información del producto</h3>
            <div class="info">
                <label for="titulo">¿Qué estás venciendo?</label>
                <span id="tit">0</span>
                <p>/50</p>
            </div>
            <div class="titulo">
                <input class="campoRellenar" id="titulo" type="text" name="titulo" maxlength="50" placeholder="Sé breve y preciso...">
            </div>
            <br>
            <div class="info">
                <label for="descripcion">Descripción</label>
                <span id="desc">0</span>
                <p>/400</p>
            </div>
            <div class="textarea">
                <textarea class="campoRellenar" name="descripcion" id="descripcion" rows="6" maxlength="400" placeholder="Indica características como el estado, color, capacidad..."></textarea>
            </div>
            <br>
            <div class="info">
                <label for="precio">Precio</label>
            </div>

            <div class="titulo">
                <input class="campoRellenar" id="precio" type="number" name="precio" placeholder="Que sea razonado">
            </div>
            <p style="color: red; text-align: center"></p>

            <div class="pesaje">
                <h4>¿Cuanto pesa tu producto?</h4>
                <p>Es importante saberlo ya que puede ser que al comprador le merece la pena usar alguna empresa de transporte...</p>
                <section>
                    <div>
                        <p>Menos de 2 kg</p>
                        <input type="radio" name="peso" value="0">
                    </div>
                    <div>
                        <p>2 a 5 kg</p>
                        <input type="radio" name="peso" value="2">
                    </div>
                    <div>
                        <p>5 a 10 kg</p>
                        <input type="radio" name="peso" value="5">
                    </div>
                    <div>
                        <p>10 a 20 kg</p>
                        <input type="radio" name="peso" value="10">
                    </div>
                    <div>
                        <p>20 a 30 kg</p>
                        <input type="radio" name="peso" value="20">
                    </div>
                    <div>
                        <p>30 a 50 kg</p>
                        <input type="radio" name="peso" value="30">
                    </div>
                    <div>
                        <p>Más de 50 kg</p>
                        <input type="radio" name="peso" value="50">
                    </div>
            </div>
            <p style="color: red; text-align: center"></p>
        </section>

        <section class="sPublic3">
            <h3>Fotos</h3>
            <p>Elija al menos una foto (Recomendado 3). Formato jpg </p>
            <section>
                <input type="file" name="img1" accept="image/jpeg">
                <input type="file" name="img2" accept="image/jpeg">
                <input type="file" name="img3" accept="image/jpeg">
                <input type="file" name="img4" accept="image/jpeg">
                <input type="file" name="img5" accept="image/jpeg">
                <input type="file" name="img6" accept="image/jpeg">
            </section>
            <p style="color: red; text-align: center"></p>
        </section>

        <section class="sPublic4">
            <button>Subir producto</button>
        </section>

    </form>

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
<script src="../JS_APP/public.js"></script>

</html>