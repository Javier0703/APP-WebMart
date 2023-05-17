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
            header("Location:error.php");
        }

    }
}

else{
    header("Location:../cierre.php");
}

if (isset($_POST["id_prod"]) && isset($_POST["id_usu"]) && strlen(trim($_POST["id_prod"]))>0 && strlen(trim($_POST["id_usu"]))>0){

    if (is_numeric($_POST["id_prod"]) && is_numeric($_POST["id_usu"])){
        $idSes=IDUSU;

        if ($_POST["id_usu"]==$idSes){
            $con=conexUsu();
            $prod=$_POST["id_prod"];
            $sql="SELECT ID_PROD,ID_USU,ID_COMPRADOR FROM productos where ID_PROD=$prod";
            $fila = $con->query($sql)->fetch_assoc();

            if ($fila){

                if($fila["ID_USU"]!=$idSes){
                    $compFav="SELECT ID_PROD, ID_USU FROM favoritos WHERE ID_PROD=$prod AND ID_USU=$idSes";
                    $filaFav = $con->query($compFav)->fetch_assoc();
                    if ($filaFav){
                        $delete = "DELETE FROM favoritos WHERE ID_PROD=$prod AND ID_USU=$idSes";
                        $con->query($delete);
                    }

                    else{
                        $insert = "INSERT INTO favoritos(id_prod, id_usu) VALUES($prod,$idSes)";
                        $con->query($insert);

                    }

                }

            }

            $con->close();
        }
    }

}