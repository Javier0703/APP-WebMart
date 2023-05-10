<?php
setcookie("correo",false);

include ("../../conexDB.php");
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
        header("Location:../../error.php");
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
                    header("Location: ../../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    $st->close();
                    $con->close();
                    header("Location: ../../index.php");
                }

                $st->close();
                $con->close();
                define("USU",$usu);
                define("PASS",$pass);
                define("ID",$id_usu);

            }


        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Nose");
            header("Location:../../error.php");
        }
    }
}

else{
    header("Location:../../cierre.php");
}


    $con = conexUsu();
    $id_usu=ID;

    if ($_FILES['icono']['error'] === UPLOAD_ERR_OK) {
        $file_info = getimagesize($_FILES['icono']['tmp_name']);
        if (!$file_info){
            setcookie("img","No hemos podido guardar la imagen, comprueba si realmente es un jpg");
        }
        if ($file_info['mime'] !== 'image/jpeg') {
            setcookie("img","No hemos podido guardar la imagen, comprueba si realmente es un jpg");
        }
        else{
            $img = file_get_contents($_FILES['icono']['tmp_name']);
            $sql = "UPDATE usuarios SET ICONO=? WHERE ID_USU=$id_usu";
            $st = $con->prepare($sql);
            $st->bind_param("s",$img);
            $st->execute();
            $st->close();
        }
    }

    $str = $_POST["nombre"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);
    if(strlen($dec) <= 30){
        $sql = "UPDATE usuarios SET NOMBRE=? WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->bind_param("s",$dec);
        $st->execute();
        $st->close();
    }


    $str = $_POST["ape1"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);
    if(strlen($dec) <= 30){
        $sql = "UPDATE usuarios SET APELLIDO1=? WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->bind_param("s",$dec);
        $st->execute();
        $st->close();
    }


    $str = $_POST["ape2"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);
    if(strlen($dec) <= 30){
        $sql = "UPDATE usuarios SET APELLIDO2=? WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->bind_param("s",$dec);
        $st->execute();
        $st->close();
    }

    $str = $_POST["correo"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $decA= strip_tags($html);
    $dec = preg_match('/^(?=.{1,40}$)[\wñÑ-]+(\.[\wñÑ-]+)*@[\wñÑ-]+(\.[\wñÑ-]{2,})+$/',$decA);

    if (strlen($_POST["correo"])===0){
        $sql = "UPDATE usuarios SET CORREO=null WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->execute();
        $st->close();
    }

    if ($dec===1){
        if(strlen($decA) <= 40){
            $sql= "SELECT CORREO FROM usuarios WHERE CORREO=? AND ID_USU!=$id_usu";
            $st = $con->prepare($sql);
            $st->bind_param("s",$decA);
            $st->execute();
            if ($st->fetch()){
                $st->close();
                setcookie("correo","Ese correo está en uso, lo siento :(");
                header("Location: perfil.php");
            }
            else{
                $st->close();
                $sql = "UPDATE usuarios SET CORREO=? WHERE ID_USU=$id_usu";
                $st = $con->prepare($sql);
                $st->bind_param("s",$decA);
                $st->execute();
                $st->close();
            }
        }

    }



    $str = $_POST["desc"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);
    if(strlen($dec) <= 400){
        $sql = "UPDATE usuarios SET DESCRIPCION=? WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->bind_param("s",$dec);
        $st->execute();
        $st->close();
    }

    $str = $_POST["direccion"];
    $rpl = str_replace('&nbsp', '', $str);
    $html = htmlspecialchars_decode($rpl);
    $dec= strip_tags($html);
    if(strlen($dec) <= 400){
        $sql = "UPDATE usuarios SET DIRECCION=? WHERE ID_USU=$id_usu";
        $st = $con->prepare($sql);
        $st->bind_param("s",$dec);
        $st->execute();
        $st->close();
    }
    $con->close();
    header("Location: perfil.php");




