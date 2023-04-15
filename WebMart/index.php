<?php
session_start();
if (isset($_COOKIE["error"])){setcookie("error", false);}
if (isset($_COOKIE["usu"])){setcookie("usu",false);}
if (isset($_COOKIE["pass"])){setcookie("pass",false);}

if (isset($_SESSION["usu"]) && isset($_SESSION["pass"])){
    header("Location:config.php");
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
            <a class="liActive" href="index.php"><li>Inicio</li></a>
            <a href="información.html"><li>Información</li></a>
            <a href="contacto.php"><li>Contacto</li></a>
        </ul>
    </nav>
</header>

<main class="mIndex" id="mIndex">
    <div id="indexDiv">
        <section id="login">
            <form action="config.php" method="POST">
                <label for="usu">Usuario o Email</label><br>
                <input type="text" name="usu" id="usu"><br>
                <label for="pass">Contraseña</label> <br>
                <input type="password" name="pass" id="pass"> <br>
                <input type="checkbox" name="sesion" id="sesion"> Mantener la sesión iniciada. <br>
                <button>Iniciar Sesión</button>
                <p id="pReg">Registrarse</p>
            </form>
        </section>

        <section id="registro" class="atras">
            <form action="config.php" method="POST">
                <label for="usuR">Usuario</label><br>
                <input type="text" name="usuR" id="usuR"><br>
                <label for="passR">Contraseña</label> <br>
                <input type="password" name="passR" id="passR"><br>
                <input type="password" name="passR2" id="passR2"><br>
                <input type="checkbox" name="sesionR" id="sesionR"> Mantener la sesión iniciada.<br>
                <button>Registrarse</button>
                <p id="pLog">Iniciar Sesion</p>
            </form>
        </section>
    </div>
    <p></p>
    <?php
    if (isset($_COOKIE["msg"])){
        echo "<p>".$_COOKIE["msg"]."</p>";
        setcookie("msg",false);
    }
    ?>
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
<script src="JS/index.js"></script>
</html>