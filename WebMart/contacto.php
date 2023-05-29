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
            <a href="index.php"><li>Inicio</li></a>
            <a href="información.html"><li>Información</li></a>
            <a class="liActive" href="contacto.php"><li>Contacto</li></a>
        </ul>
    </nav>

    <img id="desplegable" class="desplegable" src="IMG/LOGOS_ERRORES/despleg.png" alt="Desplegable">

</header>

<main id="mContacto">

    <section class="cSection1">
        <p>Contacto</p>
    </section>

    <section class="cSection2">
        <p>Puedes ponerte en contacto para lo que sea mediante nuestro correo <b>appwebmart@gmail.com</b></p>
    </section>

    <section class="cSection3">
        <p>Credenciales olvidadas</p>
        <p class="info">Genera un correo con los máximos detalles posibles, para ver si podemos ayudarte</p><br>
        <label for="titleEmail">Asunto</label><br>
        <input type="text" id="titleEmail" placeholder="Asunto destacado"><br><br>
        <label for="descEmail">Descripción</label><br>
        <textarea id="descEmail" rows="10" placeholder="Detalla lo máximo posible..."></textarea>
        <div>
            <button id="generateEmail">Generar</button>
        </div>
    </section>

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
<script src="JS/openMenu.js"></script>
<script src="JS/generateEmail.js"></script>
</html>
