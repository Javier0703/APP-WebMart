<!--Cerramos todas las SESSIONES abiertas-->
<?php
session_start();
session_destroy();
header("Location:index.php");