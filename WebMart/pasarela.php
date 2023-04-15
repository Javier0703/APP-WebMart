<?php
include ("conexDB.php");
session_set_cookie_params(sesTime());
session_start();

//Archivo de configuración para las sesiones

if (isset($_SESSION["usu"]) && isset($_SESSION["pass"])){

    if(conexUsu()==2002){
        $cod=conexUsu();
        setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location:error.php");
    }

    else{

        try{
            $con=conexUsu();
            $sql="SELECT COUNT(*) AS NUM, ROL, ESTADO FROM usuarios WHERE USUARIO=? AND CONTRASEÑA=?";
            $st=$con->prepare($sql);
            $st->bind_param("ss",$_SESSION["usu"],$_SESSION["pass"]);
            $st->execute();
            $st->bind_result($count,$rol,$estado);
            $st->fetch();
            $st->close();
            $con->close();
            if($count==1){

                if($estado==1){

                    if (isset($_COOKIE["pasarela"])){
                        setcookie("pasarela",false);
                        if($rol==0){
                            header("Location: W_USER/index.php");
                        }

                        elseif($rol==1){
                            header("Location: W_ADMIN/index.php");
                        }
                    }

                    else{
                        $usu=$_SESSION["usu"];
                        $pass=$_SESSION["pass"];
                        $usuCif=base64_encode("$usu");
                        $passCif=base64_encode("$pass");
                        setcookie("usu",$usuCif);
                        setcookie("pass",$passCif);
                        session_destroy();
                        if($rol==0){
                            session_set_cookie_params(sesTime());
                            session_start();
                            header("Location: W_USER/index.php");
                        }
                        elseif($rol==1){
                            session_set_cookie_params(sesTime());
                            session_start();
                            header("Location: W_ADMIN/index.php");
                        }

                    }

                }

                else{
                    //Tu cuenta has sido bloqueada
                    $msg="Tu cuenta ha sido bloqueada";
                    setcookie("msg",$msg);
                    header("Location: cierre.php");
                }
            }

            else{
                header("Location: cierre.php");
            }
        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location: error.php");
        }
    }

}

else{
    header("Location:cierre.php");
}
