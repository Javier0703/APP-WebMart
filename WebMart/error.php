<?php
if (!isset($_COOKIE["error"])){
    header("Location:index.php");
}

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="IMG/logo.png">
    <title>WebMart</title>
    <link rel="stylesheet" href="CSS/index.css">
</head>
<body>
<header>
    <a href="index.php"><img src="IMG/logoEntero.png" alt="Logo"></a>
    <nav>
        <ul>
            <a href="index.php"><li>Inicio</li></a>
            <a href="información.html"><li>Información</li></a>
            <a href="contacto.php"><li>Contacto</li></a>
        </ul>
    </nav>
</header>

<main id="mError">
    <div>
        <img src="IMG/error.png" alt="Error"><br>
        <?php
        if (isset($_COOKIE["error"])){
            echo $_COOKIE["error"];
        }
        ?>
    </div>
</main>

<footer>
    <div>
        <p>Creado por Javier Calvo Porro</p>
        <table>
            <tr>
                <td><img id="github" src="IMG/github.png" alt="Github"></td>
                <td>Github</td>
            </tr>
        </table>
    </div>
</footer>
</body>
</html>