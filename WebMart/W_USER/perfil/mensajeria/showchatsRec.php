<?php
include ("../../../conexDB.php");
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
?>
<h3>En búsqueda de ventas...</h3>
<?php
$con=conexUsu();
$idSes = IDUSU;
$sql = "SELECT f.FOTO, u.USUARIO, p.TITULO, c.ID_CHAT, c.ID_PROD, c.ID_USU, c.ULTIMA_CONEX_USU, c.ULTIMA_CONEX_PROD, m.ID_MENSAJE, m.MENSAJE, m.ID_ENVIADOR, m.HORA
                    FROM chats c LEFT JOIN mensajes m ON c.ID_CHAT = m.ID_CHAT JOIN productos p ON c.ID_PROD = p.ID_PROD JOIN fotos f ON p.ID_PROD = f.ID_PROD
                    join usuarios u on c.ID_USU = u.ID_USU
                    WHERE (m.ID_MENSAJE IN (SELECT MAX(ID_MENSAJE) from mensajes WHERE m.ID_CHAT=c.ID_CHAT GROUP BY ID_CHAT) OR m.ID_CHAT IS NULL)
                    AND c.ID_PROD IN (SELECT ID_PROD from productos p WHERE p.ID_USU=$idSes) GROUP BY c.ID_CHAT ORDER BY m.HORA desc";
$res= $con->query($sql);
$nR = $res->num_rows;

if ($nR > 0){
    $fila = $res->fetch_assoc();

    while ($fila){
        ?>
        <a href="chat.php?id_chat=<?=$fila["ID_CHAT"]?>">

            <div style="background-image: url('data:image/jpg;base64,<?=base64_encode($fila["FOTO"])?>')">

            </div>

            <div>

                <section>

                    <p>
                        <?=$fila["USUARIO"].": ".$fila["TITULO"]?>
                    </p>
                    <span>
                                           <?php
                                           date_default_timezone_set('Europe/Madrid');
                                           $fechaHoy = date('Y-m-d');
                                           $fechaAyer = date('Y-m-d', strtotime('-1 day'));

                                           $fechaDB = strtotime($fila["HORA"]);
                                           $fechaF= date('Y-m-d', $fechaDB);

                                           if ($fechaF == $fechaHoy){
                                               $hora = date('H', strtotime($fila["HORA"]));
                                               $minutos = date('i', strtotime($fila["HORA"]));
                                               echo $hora.":".$minutos;
                                           }

                                           elseif ($fechaF == $fechaAyer){
                                               echo "Ayer";
                                           }

                                           else{
                                               echo date('d/m', strtotime($fila["HORA"]));
                                           }

                                           ?>
                                       </span>

                </section>

                <?php
                if ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_PROD"]<=$fila["HORA"]){
                    ?>
                    <p style="font-weight: bold"><?=$fila["MENSAJE"]?></p>
                    <?php
                }
                elseif ($fila["ID_ENVIADOR"]!=$idSes && $fila["ULTIMA_CONEX_PROD"]>$fila["HORA"]){
                    ?>
                    <p><?=$fila["MENSAJE"]?></p>
                    <?php
                }
                elseif ($fila["ID_ENVIADOR"]==$idSes){
                    ?>
                    <p>Tú: <?=$fila["MENSAJE"]?></p>
                    <?php
                }
                ?>

            </div>

        </a>
        <?php
        $fila = $res->fetch_assoc();
    }
    $res->close();

}

else{
    ?>
    <div class="noResult">
        <p>Vaya... parece que nadie ha contactado con usted :(</p>
        <img src="../../../IMG/LOGOS_ERRORES/noChat2.jpg" alt="noFound">
    </div>
    <?php
}
?>
