<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_POST["id_prod"]) && strlen(trim($_POST["id_prod"]))>0 && is_numeric($_POST["id_prod"])){
    if ($_POST["id_prod"]<1){
        header("Location: prod.php");
    }
}

else{
    header("Location: prod.php");
}

$p = $_POST["id_prod"];

$con=conexUsu();
$sql = "SELECT ID_PROD, ID_COMPRADOR FROM productos WHERE ID_PROD=$p";
$res = $con->query($sql);
$fila = $res->fetch_assoc();

if ($fila){
    echo "ESE PRODUCTO EXISTE <br>";
    if ($fila["ID_COMPRADOR"]!=null){
        echo "ESE PRODUCTO ESTA VENDIDO por".$fila["ID_COMPRADOR"];
        setcookie("block","block");
        $con->close();
        header("Location: block.php");
        exit;
    }
}

else{
    $con->close();
    header("Location: prod.php?=$p");
    exit;
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
        header("Location: ../error.php");
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
            header("Location: ../error.php");
        }

    }
}

else{
    header("Location:../cierre.php");
}


$prod = $_POST["id_prod"];
$idSes = IDUSU;

//Buscamos si el producto es nuestro
$con=conexAdmin();
$sql = "SELECT ID_PROD FROM productos WHERE ID_PROD=$prod AND ID_USU=$idSes";
$res = $con->query($sql);
$nR = $res->num_rows;
$res->close();

echo "<br>";
echo $nR;

if ($nR==1){

        //Eliminar todos los registros antes del producto en sí:

        //Fotos
        $deleteFotos="DELETE FROM fotos WHERE ID_PROD=$prod";
        $res1 = $con->query($deleteFotos);


        //Favoritos
        $deleteFav="DELETE FROM favoritos WHERE ID_PROD=$prod";
        $res2 = $con->query($deleteFav);


        //Reservas
        $deleteRes="DELETE FROM reservas WHERE ID_PROD=$prod";
        $res3 = $con->query($deleteRes);


        //Opiniones
        $deleteOps="DELETE FROM opiniones WHERE ID_PROD=$prod";
        $res4 = $con->query($deleteOps);


        //Mensajes
        $deleteMsm="DELETE FROM mensajes WHERE ID_CHAT IN (SELECT ID_CHAT FROM chats where ID_PROD=$prod)";
        $res5 = $con->query($deleteMsm);


        //Chats
        $deleteMsm="DELETE FROM chats WHERE ID_PROD=$prod";
        $res6 = $con->query($deleteMsm);

        //Chats
        $deleteProd="DELETE FROM productos WHERE ID_PROD=$prod";
        $res6 = $con->query($deleteProd);

        $con->close();
        header("Location: perfil/productos.php");

}

$con->close();

setcookie("block","No hemos podido eliminar el producto porque no es tuyo ");
header("Location: block.php");




