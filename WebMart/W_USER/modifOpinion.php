<?php
include("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_POST["id_prod"]) && isset($_POST["valoracion"]) && isset($_POST["descripcion"]) &&
    strlen(trim($_POST["id_prod"]))>0 && strlen(trim($_POST["valoracion"]))>0 && strlen(trim($_POST["descripcion"]))>0){

    $p = $_POST["id_prod"];

    if (!is_numeric($p)){
        header("Location: opinion.php?id_prod=$p");
    }

    if (!is_numeric($_POST["valoracion"])){
        header("Location: opinion.php?id_prod=$p");
    }

    if (!($_POST["valoracion"]>=1 &&$_POST["valoracion"]<=5)){
        header("Location: opinion.php?id_prod=$p");
    }

    $str = $_POST["descripcion"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);

    if (strlen($dec)>400 || strlen($dec)<=0){
        header("Location: opinion.php?id_prod=$p");
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
    header("Location: index.php");
}
$res->close();

$str = $_POST["descripcion"];
$rpl = str_replace('&nbsp', '', $str);
$html = htmlspecialchars_decode($rpl);
$dec= strip_tags($html);

$sql="SELECT ID_PROD, ID_USU FROM opiniones WHERE ID_PROD=$p AND ID_USU=$idSes";
$res = $con->query($sql);
$numRows= $res->num_rows;

if ($numRows==0){
    $res->close();
    $sql="INSERT INTO opiniones VALUES(?,?,?,?)";
    $st = $con->prepare($sql);
    $st->bind_param("iiis", $p, $idSes, $_POST["valoracion"], $dec);
    $st->execute();
    $st->close();
    $con->close();
    header("Location: opinion.php?id_prod=$p");
}

else{
    $res->close();
    $val=$_POST["valoracion"];
    $sql="UPDATE opiniones SET VALORACION=?, MENSAJE=? WHERE ID_PROD=$p AND ID_USU=$idSes";
    $st = $con->prepare($sql);
    $st->bind_param("ss", $val, $dec);
    $st->execute();
    $st->close();
    $con->close();
    header("Location: opinion.php?id_prod=$p");
}