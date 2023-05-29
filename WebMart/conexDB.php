<?php
error_reporting(0);
$driver = new mysqli_driver();
$driver->report_mode= MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT;
function conexAdmin(){

    $host="localhost";
    $usu="WEBMARTADMIN";
    $pass="12345+WebMartAdmin";
    $db="WEBMART";

    try{
        $con=new mysqli("$host","$usu","$pass","$db");
        return $con;
    }
    catch (mysqli_sql_exception $e){
        return 0;
    }
}

function conexUsu(){
    $host="localhost";
    $usu="WEBMARTUSER";
    $pass="12345+WebMartUser";
    $db="WEBMART";

    try{
        $con=new mysqli("$host","$usu","$pass","$db");
        return $con;
    }

    catch (mysqli_sql_exception $e){
        return 0;
    }

}

function sesTime(){
    $time= 7 * 24 * 60 * 60;
    return $time;
}




