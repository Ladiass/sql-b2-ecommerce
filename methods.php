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
        case "editproduct":
            Products::Edit($_GET["id"],$_POST);
            break;
        case "addcard":
            Cart::add($_POST);
            break;
        case "cancelcart":
            Cart::cancel($_GET["id"]);
            break;
        case "emptycart":
            Cart::empty();
            break;
        case "checkoutcart":
            Cart::checkout($_GET["pid"]);
            break;
        case "active":
            Products::active($_GET["status"],$_GET["id"]);
            break;
        default:
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            break;
    }