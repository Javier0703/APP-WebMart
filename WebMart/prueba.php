<?php
include ("conexDB.php");
session_start();

$con = conexUsu();
$sql="SELECT c.ID_CAT, c.NOMBRE, s.ID_SUB, s.NOMBRE FROM categorias c JOIN subcategorias s using (id_cat)";
$st=$con->prepare($sql);
$st->execute();
$st->bind_result($idCat,$nombreCat,$idSub,$nombreSub);

while ($st->fetch()){
    echo "<div id='$idCat' class='Categoria'>$nombreCat</div>";
    $cat=$idCat;
    echo "<section>";
    echo "<div id='$idCat' class='Categoria'>Todas las categorías</div>";
    while ($cat==$idCat){
        echo "<div id='$idSub'>$nombreSub</div>";
        $st->fetch();
    }
    echo "</section>";
}

?>

<style>
    div{
        background:red;
    }
    section>div{
        background: lightgreen;
    }

    section{
        display: none;
    }
</style>
