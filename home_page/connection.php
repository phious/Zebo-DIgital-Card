<?php

$database= new mysqli("localhost","root","","podiatry");
if ($database->connect_error){
    die("Connection failed:  ".$database->connect_error);
}
?>