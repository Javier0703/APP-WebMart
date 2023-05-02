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

//Comprobación del contenido de las Categorías, productos y texto

if (isset($_GET["price_min"]) && !is_numeric($_GET["price_min"])){ setcookie("block","Bloqueado"); header("Location: block.php");}
if (isset($_GET["price_max"]) && !is_numeric($_GET["price_max"])){ setcookie("block","Bloqueado"); header("Location: block.php");}

if (isset($_GET["price_min"])){$_GET["price_min"]=abs($_GET["price_min"]);}
if (isset($_GET["price_max"])){$_GET["price_max"]=abs($_GET["price_max"]);}

if (isset($_GET["price_min"]) && isset($_GET["price_max"])){
    if ($_GET["price_max"] < $_GET["price_min"]){
        $priceMax = $_GET["price_max"];
        $_GET["price_max"]=$_GET["price_min"];
        $_GET["price_min"]= $priceMax;
    }
}

if (isset($_GET["id_cat"]) && !is_numeric($_GET["id_cat"])){ setcookie("block","Bloqueado"); header("Location: block.php");}
if (isset($_GET["id_sub"]) && !is_numeric($_GET["id_sub"])){ setcookie("block","Bloqueado"); header("Location: block.php");}

if (isset($_GET["id_cat"])){
    $con=conexUsu();
    $sql="SELECT COUNT(*) FROM categorias";
    $st=$con->prepare($sql);
    $st->execute();
    $st->bind_result($num);
    $st->fetch();
    $st->close();
    $con->close();
    if ($_GET["id_cat"] > $num || $_GET["id_cat"]<=0){
        setcookie("block","Bloqueado");
        header("Location: block.php");
    }
}

if (isset($_GET["id_sub"])){
    $con=conexUsu();
    $sql="SELECT COUNT(*) FROM subcategorias";
    $st=$con->prepare($sql);
    $st->execute();
    $st->bind_result($num);
    $st->fetch();
    $st->close();
    $con->close();
    if ($_GET["id_sub"] > $num || $_GET["id_sub"]<=0){
        setcookie("block","Bloqueado");
        header("Location: block.php");
    }

}

if (isset($_GET["order"]) && !is_numeric($_GET["order"])){ setcookie("block","Bloqueado"); header("Location: block.php");}

if (isset($_GET["order"]) && ($_GET["order"]>4 || $_GET["order"]<1)){
    setcookie("block","Bloqueado");
    header("Location: block.php");
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

<main class="mProd">
    <section class="sProd1">

        <form action="productos.php" method="GET">

            <div class="grid1">
                <img src="../IMG/LOGOS_ERRORES/lupa.png" alt="Lupa">
                <input type="text" name="prod" placeholder="Estoy buscando..." value="<?php if (isset($_GET["prod"]) && strlen(trim($_GET["prod"]))>0){echo $_GET["prod"];}?>">
                <img src="../IMG/LOGOS_ERRORES/x.png" alt="Lupa"<?php if (isset($_GET["prod"]) && strlen(trim($_GET["prod"]))>0){echo 'style="opacity: 1"';}?>>
            </div>

            <div class="grid2 <?php if (isset($_GET["price_min"]) || (isset($_GET["price_max"]))){echo "typeExist";} ?>">
                <section>
                    <span class="material-symbols-outlined">monetization_on</span>
                    <?php
                    if (isset($_GET["price_min"]) || (isset($_GET["price_max"]))){
                        if (isset($_GET["price_min"]) && (isset($_GET["price_max"]))){echo "<p>".$_GET["price_min"]."€ - ".$_GET["price_max"]."€"."</p>";}
                        elseif (isset($_GET["price_min"])){echo "<p>"."< ".$_GET["price_min"]."</p>";}
                        else {echo "<p>"."> ".$_GET["price_max"]."</p>";}
                    }
                    else{
                        echo "<p>Precio</p>";
                    }
                    ?>
                    <span class="material-symbols-outlined expandMore">expand_more</span>
                </section>
            </div>

            <section class="typePrice">
                <div>
                    <section class="sPrice1">
                        <h3>Precio</h3>
                        <span class="material-symbols-outlined">close</span>
                    </section>
                    <section class="sPrice2">
                        <p>¿Cuánto deseas pagar?</p>
                        <div>
                            <input id="inputMin" type="number" placeholder="Desde"  onkeydown="return event.key !== '-'"
                                <?php
                                if (isset($_GET["price_min"])){
                                    echo "value=".$_GET["price_min"];
                                }
                                ?>
                            >
                            <span class="material-symbols-outlined">euro_symbol</span>
                        </div>
                        <div>
                            <input id="inputMax" type="number" placeholder="Hasta"  onkeydown="return event.key !== '-'"
                                <?php
                                if (isset($_GET["price_max"])){
                                    echo "value=".$_GET["price_max"];
                                }
                                ?>
                            >
                            <span class="material-symbols-outlined">euro_symbol</span>
                        </div>
                        <section>
                            <div class="1">Restablecer</div>
                            <button class="2">Aplicar</button>
                        </section>
                    </section>
                </div>
            </section>

            <input id="priceMin" type="hidden" name="<?php if (isset($_GET["price_min"])){ echo "price_min";} ?>" value="<?php if (isset($_GET["price_min"])){ echo $_GET["price_min"];} ?>">
            <input id="priceMax" type="hidden" name="<?php if (isset($_GET["price_max"])){ echo "price_max";} ?>" value="<?php if (isset($_GET["price_max"])){ echo $_GET["price_max"];} ?>">

            <div class="grid3<?php if (isset($_GET["id_sub"]) || isset($_GET["id_cat"])){ echo " typeExist";} ?>">
                <section>
                    <span class="material-symbols-outlined">format_list_bulleted</span>
                    <p id="catProd">
                        <?php
                        if (isset($_GET["id_sub"]) || isset($_GET["id_cat"])){
                            $id = $_GET["id_sub"] ?? $_GET["id_cat"];
                            $con=conexUsu();
                            if (isset($_GET["id_sub"])){
                                $sql="SELECT NOMBRE FROM subcategorias where ID_SUB=?";
                                $st=$con->prepare($sql);
                                $st->bind_param("i",$id);
                                $st->execute();
                                $st->bind_result($n);
                                $st->fetch();
                                echo $n;
                                $st->close();
                            }
                            else{
                                $sql="SELECT NOMBRE FROM categorias where ID_CAT=?";
                                $st=$con->prepare($sql);
                                $st->bind_param("i",$id);
                                $st->execute();
                                $st->bind_result($n);
                                $st->fetch();
                                echo $n;
                                $st->close();
                            }
                            $con->close();
                        }
                        else{
                            echo "Todas las Categorías";
                        }
                        ?>
                    </p>
                    <span class="material-symbols-outlined expandMore">expand_more</span>
                </section>
            </div>

            <section class="popUp">
                <div>
                    <section class="sec1">
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
                    <section class="sec2">
                        <section id="allCats"><p>Todas las Categorías</p></section>
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
            <input id="typeProd" type="hidden"
                   name="<?php if (isset($_GET["id_cat"])){ echo "id_cat"; }  else if (isset($_GET["id_sub"])){ echo "id_sub";} ?>"
                   value="<?php if (isset($_GET["id_cat"])){ echo $_GET["id_cat"]; }  else if (isset($_GET["id_sub"])){ echo $_GET["id_sub"];} ?>">

            <div class="grid4"><button>Buscar</button></div>

            <section class="ordenProd">
                <div>
                    <p>Ordenado por: &nbsp</p>
                    <p class="orderSelected">
                        <?php
                        if (isset($_GET["order"])){
                            if ($_GET["order"]==1){echo "Fecha: nuevos primero";}
                            elseif ($_GET["order"]==2){echo "Fecha: antiguos primero";}
                            elseif ($_GET["order"]==3){echo "Precio: baratos primero";}
                            else{echo "Precio: caros primero";}
                        }
                        else{
                            echo "Fecha: nuevos primero";
                        }
                        ?>
                    </p>
                    <span class="material-symbols-outlined orderBy">expand_more</span>
                </div>
                <div id="popupOrder">
                    <section id="secOrder">
                        <nav><p>ORDENADO POR</p><span class="material-symbols-outlined">close</span></nav>
                        <div>
                            <span class="material-symbols-outlined">calendar_month</span>
                            <p id="1" class="<?php if (isset($_GET["order"]) && $_GET["order"]==1){echo "orderSelected";}?>">Fecha: nuevos primero</p>
                        </div>
                        <div>
                            <span class="material-symbols-outlined">calendar_month</span>
                            <p id="2" class="<?php if (isset($_GET["order"]) && $_GET["order"]==2){echo "orderSelected";}?>">Fecha: antiguos primero</p>
                        </div>
                        <div>
                            <span class="material-symbols-outlined">paid</span>
                            <p id="3" class="<?php if (isset($_GET["order"]) && $_GET["order"]==3){echo "orderSelected";}?>">Precio: baratos primero</p>
                        </div>
                        <div>
                            <span class="material-symbols-outlined">paid</span>
                            <p id="4" class="<?php if (isset($_GET["order"]) && $_GET["order"]==4){echo "orderSelected";}?>">Precio: caros primero</p>
                        </div>
                    </section>
                </div>
                <a href="productos.php">Eliminar los filtros</a>
            </section>
            <input id="orderHidden" type="hidden" name="order" value="<?php if (isset($_GET["order"])){echo $_GET["order"];}else{echo"1";}?>">

        </form>
    </section>

    <section class="sProd2">
    <?php
        $sql=""
    ?>



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
<script src="../JS_APP/prod.js"></script>
</html>
