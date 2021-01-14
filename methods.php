<?php
    include "controllers/controllers.php";

    switch($_GET["action"]){
        case "login":
            User::Login($_POST);
            break;
        case "register":
            User::Register($_POST);
            break;
        case "logout":
            User::Logout();
            break;
        case "addpost":
            Products::add($_POST);
            break;
        case "delproduct":
            Products::delete($_GET["id"]);
            break;
        default:
            header("Location: /");
            break;
    }