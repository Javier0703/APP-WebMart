<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();



if (isset($_POST["id_prod"]) && strlen(trim($_POST["id_prod"]))>0 && is_numeric($_POST["id_prod"])){

    $p = $_POST["id_prod"];

    if ($_POST["id_prod"]<1){
        header("Location: prod.php?id_prod=$p");
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
    header("Location: ../cierre.php");
}

$con=conexAdmin();

$idSes=IDUSU;
$prod=$_POST["id_prod"];

$sql="SELECT ID_USU FROM productos WHERE ID_PROD=$prod AND ID_USU=$idSes";
$nR = $con->query($sql)->num_rows;

if ($nR==1){
    try {

        //Update producto
        if (isset($_POST["precio"]) && strlen(trim($_POST["precio"]))>0 && is_numeric($_POST["precio"]) && $_POST["precio"]>1 && $_POST["precio"]<999999 ){
            $precio=ceil($_POST["precio"]);
            $uP="UPDATE productos SET PRECIO=$precio WHERE ID_PROD=$prod";
            $res = $con->query($uP);
        }

        else{
            $con->close();
            header("Location: prod.php?id_prod=$prod");
        }

        //Update titulo
        if (isset($_POST["titulo"])){

            if (strlen(trim($_POST["titulo"]))==0){
                $con->close();
                header("Location: prod.php?id_prod=$prod");
            }

            $str = $_POST["titulo"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $titulo= strip_tags($html);

            if (strlen($titulo)<1 || strlen($titulo)>50){
                $con->close();
                header("Location: prod.php?id_prod=$prod");
            }

            else{
                $sql = "UPDATE productos SET TITULO=? WHERE ID_PROD=$prod";
                $st = $con->prepare($sql);
                $st->bind_param("s",$titulo);
                $st->execute();
                $st->close();
            }

        }

        else{
            header("Location: prod.php?id_prod=$prod");
        }

       //Update descripcion

        if (isset($_POST["desc"])){

            if (strlen(trim($_POST["desc"]))==0){
                $con->close();
                header("Location: prod.php?id_prod=$prod");
            }

            $str = $_POST["desc"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $desc= strip_tags($html);

            if (strlen($desc)==0 || strlen($desc)>500){
                $con->close();
                header("Location: prod.php?id_prod=$prod");
            }

            else{
                $sql = "UPDATE productos SET DESCRIPCION=? WHERE ID_PROD=$prod";
                $st = $con->prepare($sql);
                $st->bind_param("s",$desc);
                $st->execute();
                $st->close();
            }


        }

        else{
            $con->close();
            header("Location: prod.php?id_prod=$prod");
        }

        $con->close();
        header("Location: prod.php?id_prod=$prod");
    }

    catch (mysqli_sql_exception $e){
        $cod=$e ->getCode();
        $msgError=$e->getMessage();
        setcookie("error","Error $cod, $msgError");
        header("Location: ../error.php");
    }

}

else{
    $con->close();
    header("Location: productos.php");
}


