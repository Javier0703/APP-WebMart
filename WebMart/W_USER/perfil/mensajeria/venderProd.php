<?php

include("../../../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

$p = $_POST["id_prod"];
$c = $_POST["id_comp"];
$chat = $_POST["id_chat"];

if (isset($p) && isset($c) && $p>0 && $c>0 && strlen(trim($p))>0 && strlen(trim($c))>0 ){

    if (!is_numeric($p) || !is_numeric($c)){
        header("Location: chat.php");
        exit;
    }

}

else{
    header("Location: chat.php");
    exit;
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
        header("Location: ../../../error.php");
    }

    else {

        try {
            $con = conexUsu();
            $sql = "SELECT ID_USU,ESTADO,ROL FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st = $con->prepare($sql);
            $st->bind_param("ss", $usu, $pass);
            $st->execute();
            $st->bind_result($id_usu, $estado, $rol);

            if ($st->fetch()) {

                if ($rol == 1) {
                    $st->close();
                    $con->close();
                    header("Location: ../../../W_ADMIN/index.php");
                }

                if ($estado == 0) {
                    //Tu cuenta has sido bloqueada
                    $msg = "Tu cuenta ha sido bloqueada";
                    setcookie("msg", $msg);
                    $st->close();
                    $con->close();
                    header("Location: ../../../index.php");
                }

                $st->close();
                $con->close();
                define("USU", $usu);
                define("PASS", $pass);
                define("IDUSU", $id_usu);

            }

            else {
                header("Location: ../../../cierre.php");
            }

        }

        catch (mysqli_sql_exception $e) {
            $cod = $e->getCode();
            $msgError = $e->getMessage();
            setcookie("error", "Error $cod, $msgError");
            header("Location: ../../../error.php");
        }
    }
}

else {
    header("Location: ../../../cierre.php");
}

//Comprobamos si está en venta o no ese producto
$con=conexAdmin();
$comp="SELECT ID_COMPRADOR FROM productos where ID_PROD=$p";
$fila = $con->query($comp)->fetch_assoc();

if ($fila["ID_COMPRADOR"]==null){
    $con->query($comp)->close();
    //Comprobar que ese usuario tiene un chat, ha intentado contactar con el
    $showChats = "SELECT ID_CHAT FROM chats WHERE ID_USU=$c AND ID_PROD=$p";
    $nR = $con->query($showChats)->num_rows;

    if ($nR==1){
        //Hay chat para ello, comenzamos la eliminación

        //Eliminar reservas
        $deleteReservas = "DELETE FROM reservas WHERE ID_PROD=$p";
        $con->query($deleteReservas);

        //Update del id_comprador e id_reserva
        $updateProd = "UPDATE productos SET ID_COMPRADOR=$c, ID_RESERVA=$c WHERE ID_PROD=$p";
        $con->query($updateProd);

        $con->close();

        header("Location: chat.php?id_chat=$chat");

    }

    else{
        $con->close();
        header("Location: chat.php");
        exit;
    }

}

else{
    $con->close();
    header("Location: chat.php");
    exit;
}
