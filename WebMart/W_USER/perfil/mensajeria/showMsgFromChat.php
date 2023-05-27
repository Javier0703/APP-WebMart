<?php
include ("../../../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_GET["id_chat"]) && $_GET["id_chat"]>0 && strlen(trim($_GET["id_chat"]))>0){

    if (!is_numeric($_GET["id_chat"])){
        header("Location: mensajes.php");
        exit;
    }
}

else{
    header("Location: mensajes.php");
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
        header("Location: ../../../error.php");
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
                    header("Location: ../../../W_ADMIN/index.php");
                }

                if ($estado==0){
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    $st->close();
                    $con->close();
                    header("Location: ../../../index.php");
                }

                $st->close();
                $con->close();
                define("USU",$usu);
                define("PASS",$pass);
                define("IDUSU",$id_usu);

            }

            else{
                header("Location: ../../../cierre.php");
            }

        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location: ../../../error.php");
        }
    }
}

else{
    header("Location: ../../../cierre.php");
}

//Buscamos si ese chat es suyo o no

$con = conexUsu();
$chat = $_GET["id_chat"];
$idSes = IDUSU;
$sql = "SELECT * FROM chats WHERE ID_CHAT=$chat AND (ID_USU=$idSes OR ID_PROD IN (SELECT p.ID_PROD FROM productos p WHERE p.ID_USU=$idSes))";
$nR = $con->query($sql)->num_rows;
$con->close();

if ($nR == 0){
    header("Location: mensajes.php");
    exit;
}

$idSes = IDUSU;
$c = $_GET["id_chat"];
$con2=conexUsu();
$showMSG = "select ID_CHAT, ID_ENVIADOR, MENSAJE, DATE_FORMAT(HORA, '%d/%m, %H:%i') AS HORA  from mensajes WHERE ID_CHAT=$c ORDER BY HORA";
$result = $con2->query($showMSG);
$msgs = $result->fetch_assoc();
while ($msgs){
?>
    <article>
        <div <?php if ($msgs["ID_ENVIADOR"]==$idSes){?> class="miMSG" <?php } ?>>
            <p><?=$msgs["MENSAJE"]?></p>
            <div>
                <span><?=$msgs["HORA"]?></span>
            </div>
        </div>
    </article>
    <?php
    $msgs = $result->fetch_assoc();
}
$con2->close();
?>