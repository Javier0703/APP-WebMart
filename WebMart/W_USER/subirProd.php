<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_POST["id_sub"]) && strlen(trim($_POST["id_sub"]))>0 && is_numeric($_POST["id_sub"]) &&
    isset($_POST["titulo"]) && strlen(trim(strip_tags($_POST["titulo"]))) &&
    isset($_POST["descripcion"]) && strlen(trim(strip_tags($_POST["descripcion"])))>0 &&
    isset($_POST["precio"]) && strlen(trim(strip_tags($_POST["precio"])))>0 &&
    isset($_POST["peso"]) && strlen(trim($_POST["peso"]))>0 && is_numeric($_POST["precio"]) &&
    isset($_FILES["img1"])){

    if (conexUsu()==0){
        $cod=conexUsu();
        setcookie("error","Error $cod, no se puede establecer conexión con la Base de Datos :(");
        header("Location: ../error.php");
    }

    else{
        try {
            $con = conexUsu();
            $id_sub = $_POST["id_sub"];
            $sql= "SELECT ID_SUB FROM subcategorias WHERE ID_SUB=?";
            $st=$con->prepare($sql);
            $st->bind_param("i", $id_sub);
            $st->execute();
            $st->bind_result($id);

            if (!$st->fetch()){
                $st->close();
                setcookie("block","true");
                header("Location: block.php");
                exit;
            }
            $st->close();
            $con->close();

            $str = $_POST["titulo"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $dec= strip_tags($html);
            if(strlen($dec)>50 || strlen($dec)<=0){
                header("Location: publicar.php");
                exit;
            }

            $str = $_POST["descripcion"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $dec= strip_tags($html);
            if(strlen($dec)>400 || strlen($dec)<=0){
                header("Location: publicar.php");
                exit;
            }

            if (!preg_match('/^[0-9]+$/', $_POST["precio"])){
                setcookie("block","true");
                header("Location: block.php");
            }
            else{
                if (ceil($_POST["precio"])<0 || ceil($_POST["precio"])>999999){
                    header("Location: publicar.php");
                }
            }

            $pesos = array(0,2,5,10,20,30,50);
            if (!in_array($_POST["peso"],$pesos)){
                setcookie("block","true");
                header("Location: block.php");
            }

            ini_set('upload_max_filesize', '2M');

            //Comprobación imagen 1
            if ($_FILES['img1']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img1']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }
            else {
                header("Location: publicar.php");
            }

            //Comprobación imagen 2
            if ($_FILES['img2']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img2']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }

            //Comprobación imagen 3
            if ($_FILES['img3']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img3']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }

            //Comprobación imagen 4
            if ($_FILES['img4']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img4']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }

            //Comprobación imagen 5
            if ($_FILES['img5']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img5']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }

            //Comprobación imagen 6
            if ($_FILES['img6']['error'] === UPLOAD_ERR_OK) {
                $file_info = getimagesize($_FILES['img6']['tmp_name']);
                if (!$file_info){
                    header("Location: publicar.php");
                }
                if ($file_info['mime'] !== 'image/jpeg') {
                    header("Location: publicar.php");
                }
            }

            //Comprobar el id_usuario
            if (isset($_SESSION["usu"]) || isset($_COOKIE["usu"])){
                $n= $_SESSION["usu"] ?? base64_decode($_COOKIE["usu"]);
            }

            $con=conexUsu();
            $sql="SELECT ID_USU FROM usuarios WHERE USUARIO=?";
            $st=$con->prepare($sql);
            $st->bind_param("s", $n);
            $st->execute();
            $st->bind_result($id);
            if (!$st->fetch()){
                $st->close();
                header("Location: ../cierre.php");
            }

            $id_usu=$id;
            $st->close();

            date_default_timezone_set('Europe/Madrid');
            $date = date("Y-m-d H:i:s");

            $id_sub=$_POST["id_sub"];

            $str = $_POST["titulo"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $titulo= strip_tags($html);

            $str = $_POST["descripcion"];
            $rpl = str_replace('&nbsp', '', $str);
            $html = htmlspecialchars_decode($rpl);
            $descripcion= strip_tags($html);

            $peso=$_POST["peso"];
            $precio=ceil($_POST["precio"]);


            $sql="INSERT INTO productos(ID_SUB,TITULO,DESCRIPCION,PESO,PRECIO,FECHA_SUBIDA,ID_USU) VALUE(?,?,?,?,?,?,?)";
            $st=$con->prepare($sql);
            $st->bind_param("issiisi", $id_sub,$titulo,$descripcion,$peso,$precio,$date,$id_usu);
            $st->execute();
            $st->close();

            $sql="SELECT ID_PROD FROM productos WHERE ID_USU='$id_usu' ORDER BY FECHA_SUBIDA DESC";
            $res=$con->query($sql);
            $fila = $res->fetch_assoc();
            $id_prod=$fila["ID_PROD"];
            echo $id_prod;

            //Subida de imagenes
            $img = file_get_contents($_FILES['img1']['tmp_name']);
            $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
            $st=$con->prepare($sql);
            $st->bind_param("is", $id_prod,$img);
            $st->execute();
            $st->close();

            if ($_FILES['img2']['error'] === UPLOAD_ERR_OK){
                $img = file_get_contents($_FILES['img2']['tmp_name']);
                $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("is", $id_prod,$img);
                $st->execute();
                $st->close();
            }


            if ($_FILES['img3']['error'] === UPLOAD_ERR_OK){
                $img = file_get_contents($_FILES['img3']['tmp_name']);
                $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("is", $id_prod,$img);
                $st->execute();
                $st->close();

            }


            if ($_FILES['img4']['error'] === UPLOAD_ERR_OK){
                $img = file_get_contents($_FILES['img4']['tmp_name']);
                $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("is", $id_prod,$img);
                $st->execute();
                $st->close();
            }


            if ($_FILES['img5']['error'] === UPLOAD_ERR_OK){
                $img = file_get_contents($_FILES['img5']['tmp_name']);
                $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("is", $id_prod,$img);
                $st->execute();
                $st->close();
            }


            if ($_FILES['img6']['error'] === UPLOAD_ERR_OK){
                $img = file_get_contents($_FILES['img6']['tmp_name']);
                $sql="INSERT INTO fotos(ID_PROD, FOTO) VALUES(?,?)";
                $st=$con->prepare($sql);
                $st->bind_param("is", $id_prod,$img);
                $st->execute();
                $st->close();
            }
            $con->close();
            header("Location: perfil/productos.php");
            exit;
        }

        catch (mysqli_sql_exception $e){
            $cod=$e ->getCode();
            $msgError=$e->getMessage();
            setcookie("error","Error $cod, $msgError");
            header("Location: ../error.php");
            exit;
        }
    }
}


else{
    header("Location: publicar.php");
}

