<?php
$driver = new mysqli_driver();
$driver->report_mode= MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
function conexAdmin(){

    $host="localhost";
    $usu="WEBMARTADMIN";
    $pass="12345+WebMartAdmin";
    $db="webmart";

    try{
        $con=new mysqli("$host","$usu","$pass","$db");
        return $con;
    }
    catch (mysqli_sql_exception $e){
        echo $e->getCode();
    }
}

function conexUsu(){

    $host="localhost";
    $usu="WEBMARTUSER";
    $pass="12345+WebMartUser";
    $db="webmart";

    try{
        $con=new mysqli("$host","$usu","$pass","$db");
        return $con;
    }
    catch (mysqli_sql_exception $e){
        return $e->getCode();
    }

}




