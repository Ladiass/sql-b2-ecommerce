<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbName = "bew12_ecommerce";

    $db = new mysqli($host , $username , $password, $dbName);
    if($db->connect_error){
        die("$mysqli->connect_errno: $mysqli->connect_error");
    }