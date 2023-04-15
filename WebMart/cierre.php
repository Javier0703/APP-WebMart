<!--Cerramos todas las SESSIONES abiertas-->
<?php
if (isset($_COOKIE["pasarela"])){setcookie("pasarela",false);}
if (isset($_COOKIE["usu"])){setcookie("usu",false);}
if (isset($_COOKIE["pass"])){setcookie("pass",false);}
session_start();
session_destroy();
header("Location:index.php");