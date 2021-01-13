<?php
    session_start();
    include "connection.php";
    
    class User{
        public static function Login($user){
            global $db;
            session_start();
            $username = $user['username'];
            $password = $user["password"];
            if(!User::Find_user($username) && !User::Find_with_email($username)){
                $_SESSION["status"] = "User/Email Not found!";
                header("Location: /views/partials/user.php?username=$username");
            }elseif(!empty(User::Find_user($username))){
                $user = User::Find_user($username)[0];
            }else{
                $user = User::Find_with_email($username)[0];
            }
            if(!password_verify($password,$user["password"])){
                $_SESSION["status"] = "UserName Or Password is wrong!";
                header("Location: /views/partials/user.php?username=$username");
            }
            $_SESSION["status"] = "Login Success!";
            $_SESSION["user_details"] = $user;
            header("Location: /");
        }

        public static function Register($inf){
            global $db ;
            $username = $inf['username'];
            $email = $inf["email"];
            $f_name = $inf["fristname"];
            $l_name = $inf["lastname"];
            $addr = $inf["address"];

            if($inf["password"] != $inf["password2"]){
                $_SESSION["status"] = "Password are not same!";
                header("Location: /views/partials/user.php?rusername=$username");
            }
            if(strlen($inf["password"]) < 4){
                $_SESSION["status"] = "Password cant least then 4";
                header("Location: /views/partials/user.php?rusername=$username");   
            }
            $password = password_hash($inf["password"],PASSWORD_DEFAULT);
            

            if(!empty(User::Find_user($username)) || !empty(User::Find_with_email($email))){
                $_SESSION["status"] = "Username/Email has register before already !";
                header("Location: /views/partials/user.php?status=error1");
            }

            $sql = "INSERT INTO `users` (`username`,`frist_name`,`last_name`,`address`,`email`,`password`) VALUE(?, ?, ?, ?, ?, ?);";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                header("Location: /views/partials/user.php?status=error");
                exit();
            }
            $stmt->bind_param("ssssss",$username,$f_name,$l_name,$addr,$email,$password);
            $stmt->execute();
            $_SESSION["status"] = "Success to Register";

            Email::send_email($email,$f_name);
            die();
            header("Location: /");
            exit();
        }

        public static function Find_user($username){
            global $db ;
            $sql = "SELECT `username`,`password` FROM `users` WHERE `username` = ?";
            $stmt = $db->prepare($sql);
            
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $result = $stmt->get_result();

            $user = $result->fetch_all(MYSQLI_ASSOC);
            return $user;

            $stmt->close();
        }

        public static function Find_with_email($email){
            global $db ;
            $sql = "SELECT `username`,`email`,`password` FROM `users` WHERE `email` = ? ";
            $stmt = $db->prepare($sql);
            
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();

            $email = $result->fetch_all(MYSQLI_ASSOC);
            return $email;

            $stmt->close();
        }

        public static function logout(){
            session_start();
            session_unset();
            session_destroy();
            header("Location: /");
        }
    }

    require_once "vendor/autoload.php";
    class Email{
        public static function send_email($email,$username){
            //准备工作 setups!!
            $transport = new Swift_SmtpTransport('smtp.gmail.com',465,'ssl');
            $transport->setUsername("limxinze@gmail.com");
            $transport->setPassword("flmanviaavpxvunt");

            $mailer = new Swift_Mailer($transport);
            // end Setups 

            $massage = new Swift_Message("noReply");
            $massage->setFrom(["noReply@gmail.com"=>"Ai"]);
            $massage->setTo(["$email"=>"$username"]);
            $massage->setBody("thx For Registe to our website!");
            
            $result = $mailer->send($massage);
            if($result){
                return "Success to send the email , Pls check in your email !";
            }
            return "Something was Wrong,Pls content Our Admin!";
        }
    }