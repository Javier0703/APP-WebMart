<?php
include ("../conexDB.php");
session_start();

if (isset($_POST["cat"])){
    echo $_POST["cat"];
    if (is_numeric($_POST["cat"])){
        $cat=$_POST["cat"];
        $busqueda="SELECT ID_CAT, p.ID_SUB, ID_PROD, TITULO, PRECIO FROM productos p JOIN subcategorias s using (id_sub) WHERE ID_COMPRADOR is null AND ID_CAT=$cat";
        echo $busqueda;
    }
    else{
        echo "Nonono...";
    }
}

else{
    header("Location:index.php");
}