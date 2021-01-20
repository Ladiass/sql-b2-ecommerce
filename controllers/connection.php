<?php
    $host = "db4free.net";
    $username = "liadas";
    $password = "tss010430";
    $dbName = "b2ecommerce";

    $db = new mysqli($host , $username , $password, $dbName);
    if($db->connect_error){
        die("$mysqli->connect_errno: $mysqli->connect_error");
    }