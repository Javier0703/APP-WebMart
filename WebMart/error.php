<?php
if (!isset($_COOKIE["error"])){
    header("Location: index.php");
    exit;
}

if (isset($_COOKIE["error"])) {
    $error=$_COOKIE["error"];
    setcookie("error", false);
}
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="IMG/LOGOS_ERRORES/logo.png">
    <title>WebMart</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
<header>

    <a id="logo" href="index.php"><img src="IMG/LOGOS_ERRORES/logo.png" alt="Logo"></a>
    <a id="logoE" href="index.php"><img src="IMG/LOGOS_ERRORES/logoEntero.png" alt="Logo"></a>

    <nav>
        <ul>
            <a class="liActive" href="index.php">
                <li>Inicio</li>
            </a>
            <a href="información.html"><li>Información</li></a>
            <a href="contacto.php"><li>Contacto</li></a>
        </ul>
    </nav>

    <img id="desplegable" class="desplegable" src="IMG/LOGOS_ERRORES/despleg.png" alt="Desplegable">

</header>

<main id="mError">
    <div>
        <img src="IMG/LOGOS_ERRORES/error.png" alt="Error"><br>
        <?php
        if ($error){
            echo $error;
            unset($error);
        }
        ?>
    </div>
</main>

<footer>
    <div>
        <p>Creado por Javier Calvo Porro</p>
        <table>
            <tr>
                <td><img id="github" src="IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                <td>Github</td>
            </tr>
        </table>
    </div>
</footer>
</body>
</html>