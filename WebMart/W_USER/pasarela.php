<?php

include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_GET["id_prod"]) && strlen(trim($_GET["id_prod"]))>0 ){

    if (!is_numeric($_GET["id_prod"])){
        header("Location: productos.php");
    }

    if ($_GET["id_prod"]<1){
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

            else{
                header("Location: ../cierre.php");
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

//Buscamos si ese producto existe

$prod = $_GET["id_prod"];
$idSes = IDUSU;
$con=conexUsu();

$seeProduct= "SELECT ID_PROD FROM productos WHERE ID_PROD=$prod";
$res = $con->query($seeProduct);
$nR = $res->num_rows;

if ($nR==1){
    $res->close();

    //Comprobar si ese producto no esta comprado o si es nuestro, porque no se puede
    $compStat = "SELECT ID_COMPRADOR, ID_USU FROM productos WHERE ID_PROD=$prod";
    $res = $con->query($compStat);
    $row = $res->fetch_assoc();

    if ($row["ID_COMPRADOR"]==null && $row["ID_USU"]!=$idSes){
        //Comprobar si existe un chat con el producto y nosotros
        $comp= "SELECT ID_CHAT FROM chats WHERE ID_PROD=$prod AND ID_USU=$idSes";
        $res = $con->query($comp);
        $nR = $res->num_rows;
        $res->close();

        if ($nR==0){
            //Añadir el chat
            date_default_timezone_set('Europe/Madrid');
            $date = date("Y-m-d H:i:s");

            $newChat = "INSERT INTO chats(ID_PROD, ID_USU, ULTIMA_CONEX_PROD, ULTIMA_CONEX_USU) VALUES(?,?,?,?)";
            $st=$con->prepare($newChat);
            $st->bind_param("iiss",$prod,$idSes,$date,$date);
            $st->execute();

            $st->close();

            //Buscamos ese chat y entramos en él.

            $seeChat = "SELECT ID_CHAT FROM chats WHERE ID_PROD=$prod AND ID_USU=$idSes";
            $fila = $con->query($seeChat)->fetch_assoc();
            $n = $fila["ID_CHAT"];
            header("Location: perfil/mensajeria/chat.php?id_chat=$n");

        }

        else{
            $seeChat = "SELECT ID_CHAT FROM chats WHERE ID_PROD=$prod AND ID_USU=$idSes";
            $fila = $con->query($seeChat)->fetch_assoc();
            $n = $fila["ID_CHAT"];
            header("Location: perfil/mensajeria/chat.php?id_chat=$n");
        }
    }

    else{
        header("Location: productos.php");
    }

}

else{
    header("Location: productos.php");
}
