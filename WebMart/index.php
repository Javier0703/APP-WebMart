<?php
session_start();
if (isset($_COOKIE["error"])){setcookie("error", false);}
if (isset($_COOKIE["usu"])){setcookie("usu",false);}
if (isset($_COOKIE["pass"])){setcookie("pass",false);}

if (isset($_SESSION["usu"]) && isset($_SESSION["pass"])){
    header("Location:config.php");
}


if (isset($_COOKIE["msg"])) {
    $msg=$_COOKIE["msg"];
    setcookie("msg", false);
}


?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="IMG/LOGOS_ERRORES/logo.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,1,0"/>
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

<main class="mIndex" id="mIndex">
    
    <div id="indexDiv">

        <section id="login">

            <aside>
                <div>
                    <img src="IMG/LOGOS_ERRORES/fondoLogin.svg" alt="Fondo">
                </div>
                <h3 id="pReg">¡Regístrate!</h3>
            </aside>

            <form action="config.php" method="POST">
                <section>
                    <img src="IMG/LOGOS_ERRORES/logo.png" alt="Logo">
                </section>
                <label for="usu">Usuario o email</label>
                <div class="marg20">
                    <div>
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="usu" id="usu">
                    </div>
                </div>
                <label for="pass">Contraseña</label>
                <div>
                    <div>
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="pass" id="pass">
                    </div>
                </div>
                <a href="contacto.php">¿No te acuerdas?</a><br>
                <input type="checkbox" name="sesion" id="sesion"> Mantener la sesión iniciada. <br>
                <section>
                    <button id="incSes">Iniciar Sesión</button>
                </section>
            </form>

        </section>

        <section id="registro" class="atras">

            <aside>
                <div>
                    <img src="IMG/LOGOS_ERRORES/fondoLogin.svg" alt="Fondo">
                </div>
                <h3 id="pLog">Iniciar Sesión</h3>
            </aside>

            <form action="config.php" method="POST">
                <section>
                    <img src="IMG/LOGOS_ERRORES/logo.png" alt="Logo">
                </section>
                <label for="usuR">Usuario</label>
                <div class="marg20">
                    <div>
                        <span class="material-symbols-outlined">person</span>
                        <input type="text" name="usuR" id="usuR">
                    </div>
                </div>
                <label for="passR">Contraseña</label>
                <div>
                    <div id="secPass">
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="passR" id="passR">
                    </div>
                </div>
                <div>
                    <div>
                        <span class="material-symbols-outlined">lock</span>
                        <input type="password" name="passR2" id="passR2">
                    </div>
                </div>
                <input type="checkbox" name="sesionR" id="sesionR"><label for="sesionR"> Mantener la sesión iniciada.</label><br>
                <section>
                    <button id="Reg">Registrarse</button>
                </section>
            </form>
        </section>
    </div>

    <p></p>
    <?php
    if (isset($msg)){
        echo "<p>$msg</p>";
        unset($msg);
    }
    ?>
</main>

<footer>
    <div>
        <p>Creado por Javier Calvo Porro</p>
        <table>
            <tr>
                <td><img id="github" src="IMG/LOGOS_ERRORES/github.png" alt="Github"></td>
                <td><a href="https://github.com/Javier0703/APP-WebMart" target="_blank">Disponible en GitHub</a></td>
            </tr>
        </table>
    </div>
</footer>

</body>
<script src="JS/index.js"></script>
<script src="JS/openMenu.js"></script>
</html>