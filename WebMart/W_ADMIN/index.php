<?php
include ("../conexDB.php");
session_set_cookie_params(sesTime());
session_start();

if (isset($_SESSION["usu"]) && isset($_SESSION["pass"])){

}

elseif (isset($_COOKIE["usu"]) && isset($_COOKIE["pass"])){

}

else{
    header("Location: ../index.php");
}
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WebMart</title>
</head>
<body>


</body>
</html>