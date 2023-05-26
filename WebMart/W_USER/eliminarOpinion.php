<?php
include("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_POST["id_prod"]) && strlen(trim($_POST["id_prod"]))>0 ){
    $p = $_POST["id_prod"];

    if (!is_numeric($p)){

        header("Location: opinion.php?id_prod=$p");
    }

}

else{
    header("Location: perfil/misOpiniones.php");
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
        header("Location: ../error.php");
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

            else{
                header("Location: ../cierre.php");
            }
        }

        catch (mysqli_sql_exception $e) {
            $cod = $e->getCode();
            $msgError = $e->getMessage();
            setcookie("error", "Error $cod, $msgError");
            header("Location: ../error.php");
        }
    }
}

else {
    header("Location: ../cierre.php");
}

$con=conexUsu();
$p=$_POST["id_prod"];
$idSes=IDUSU;
$sql = "SELECT ID_PROD, ID_COMPRADOR FROM productos WHERE ID_PROD=$p AND ID_COMPRADOR=$idSes";
$res = $con->query($sql);
$fila = $res->fetch_assoc();
if (!$fila){
    $res->close();
    header("Location: perfil/misOpiniones.php");
    exit;
}

$res->close();
$sql="DELETE FROM opiniones WHERE ID_USU=$idSes AND ID_PROD=$p";
$res = $con->query($sql);
$con->close();

header("Location: opinion.php?id_prod=$p");




