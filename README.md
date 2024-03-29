<img src="WebMart/IMG/LOGOS_ERRORES/logo.png" align="right" width="128px"/>

# WebMart

[![GitHub Version](https://img.shields.io/badge/Version-1.0-blue)](https://img.shields.io/badge/Version-1.0-blue)
[![GitHub Contributors](https://img.shields.io/github/contributors/Javier0703/APP-WebMart?label=Contribuidores&logo=github&color=blue)](https://github.com/Javier0703/APP-WebMart/graphs/contributors)
[![GitHub Commits](https://img.shields.io/github/commit-activity/y/Javier0703/APP-WebMart?label=Commits&logo=github&color=blue)](https://github.com/Javier0703/APP-WebMart/graphs/contributors)

**WebMart** es una aplicación web diseñada con las principales y más básicas tecnologías de desarrollo. Ha sido diseñado y creado como proyecto de fin de grado para un Grado Superior de ASIR, evaluado con un **10**.

## 👨🏻‍💻 Tecnologías implementadas
<p align="center">
    <img src="README_CONTENT/html.png" height="95"/>
    <img src="README_CONTENT/css.png" height="95"/>
    <img src="README_CONTENT/php.png" height="95"/>
    <img src="README_CONTENT/js.png" height="95"/>
    <img src="README_CONTENT/sql.png" height="95"/>
    <img src="README_CONTENT/jquery.png" height="95"/>
    <img src="README_CONTENT/ajax.png" height="95"/>
</p>

Estas **tecnologias** han sido utilizadas de la siguiente manera:
* [HTML](https://desarrolloweb.com/home/html), [CSS](https://desarrolloweb.com/home/css) y [JavaScript](https://desarrolloweb.com/home/javascript) de manera vanila, sin utilizar ningun framework de ayuda o mejora visual.
* [PHP](https://www.php.net) como lenguaje back-end. Es el que he utilizado durante mi trascruso por el grado y el que más comodo de parecía.
* [AJAX](https://developer.mozilla.org/es/docs/Web/Guide/AJAX), o conocido como JavaScript y XML asíncrono, ha sido utilizado para actualizar la aplicación sin necesidad de recargar, para ciertos asceptos, como es el caso del apartado de mensajería.
* [jQuery](https://jquery.com/), una librería de JavaScript muy popular, cuyo uso ha sido simplemente para AJAX, y comprobar que diferencias había usandolo con jQuery o con JS plano.
* [SQL](https://datademia.es/blog/que-es-sql) el gestor de Base de Datos es MySQL (MariaDB), ya que es el más sencillo y fácil de manejar para aplicaciones web pequeñas.

## 🏛️ Estructuración del proyecto

Esta es la estructura básica de todo el proyecto completo:

<img src="README_CONTENT/Estructura.jpg" height="100%"/>

### 📂 BASE_DATOS
En esta carpeta encontramos todo el contenido relacionado con la [Base de Datos](https://github.com/Javier0703/APP-WebMart/tree/main/BASE_DATOS) :
* 📂 [Entidad_Relacion](https://github.com/Javier0703/APP-WebMart/tree/main/BASE_DATOS/Entidad-Relacion): Modelo entero y final de la aplicacion en formato **JPG, JSON y SVG**.
* 📂 [Relacional](https://github.com/Javier0703/APP-WebMart/tree/main/BASE_DATOS/Relacional): Modelo realizado en [Draw.io](https://www.drawio.com/). Formatos: **PNG y DRAWIO**.
* 📜 [dBase.sql](https://github.com/Javier0703/APP-WebMart/blob/main/BASE_DATOS/dbase.sql): Fichero SQL en el que se encuentran la creacion de la propia Base de Datos con:
  * Tablas
  * Usuarios con permisos
  * Categorías y subcategorías

```
CREATE TABLE PRUEBA(
    ID INT PRIMARY_KEY,
    NOMBRE VARCHAR(40) NOT NULL
);
```
### 📂 CASOS_USO
En esta podemos encontrar todos los [Casos de Uso](https://github.com/Javier0703/APP-WebMart/tree/main/Casos_Uso) que se han planteado y mostrado según se iba formando el proyecto. En el podemos encontrar un index en el que se mostrará una pantalla con todos y cada uno de estos casos para tener un acceso mucho mas sencillo y rápido.

### 📂 EXTRA_INFO
En este apartado de [Información Extra](https://github.com/Javier0703/APP-WebMart/tree/main/Casos_Uso) encontramos:
* 📜 [Cronología](https://github.com/Javier0703/APP-WebMart/blob/main/Extra_Info/WebMart_Realidad.pdf) de todas las actividades y el tiempo que me ha llevado este proyecto.
* 📜 [Análisis DAFO](https://github.com/Javier0703/APP-WebMart/blob/main/Extra_Info/DAFO.pdf) con sus debilidades, amenazas, fortalezas y oportunidades.

### 📂 README_CONTENT
Contenido para la creacion de los [Readme](https://github.com/Javier0703/APP-WebMart/tree/main/README_CONTENT). En el mayor de los casos son imagenes **PNG**.

### 📂 WEBMART
En esta carpeta encontramos realmente la propia aplicacion de [WebMart](https://github.com/Javier0703/APP-WebMart/tree/main/WebMart). En ella se muestran diversos archivos y carpetas:
* 📂  CSS: Todas las hojas de estilo utilizadas.
* 📂  JS: JavaScript, tanto jQuery como JS plano.
* 📂  IMG: Todas las imagenes utilizadas dentro de WebMart. Estan divididas en subcarpetas para tener un orden.
* 📜 PHP/HTML: Archvos necesarios para la creación de la aplicación. Cuenta con unos 45/50 archivos para su correcta funcionalidad.

