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
                exit();
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
                exit();
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
            header("Location: /");
            exit();
        }

        public static function Find_user($username){
            global $db ;
            $sql = "SELECT `user_id`,`username`,`email`,`password`,`isAdmin` FROM `users` WHERE `username` = ?";
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
            $sql = "SELECT `user_id`,`username`,`email`,`password`,`isAdmin` FROM `users` WHERE `email` = ? ";
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
            $massage->setTo([$email=>$username]);
            $massage->setBody("thx For Register to our website!");
            
            $result = $mailer->send($massage);
            if($result){
                return "Success to send the email , Pls check in your email !";
            }
            return "Something was Wrong,Pls content Our Admin!";
        }
    }

    class Products{

        public static function active($status,$id){
            global $db;
            $isActive = 1;
            if($status){
                $isActive = "0";
            }
            $sql = "UPDATE `products` SET `isActive` = ? WHERE `products`.`product_id` = ?";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                echo "Something wrong!";
                exit();
            }
            $stmt->bind_param("ii",$isActive,$id);
            $stmt->execute();
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }

        public static function add($data){
            global $db;
            $category_id = Products::find_categoryid($data["category"]);
            $path_image = Products::image($_FILES);

            $sql = "INSERT INTO `products` (`name`, `price`, `description`, `image`, `category_id`) VALUES (?,?,?,?,?)";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                header("Location: /?status=Error!");
                exit();
            }

            $stmt->bind_param("sdssi",$data["product_name"],$data["price"],$data["desc"],$path_image,$category_id);
            $stmt->execute();
            
            $_SESSION["status"] = "Success to Add the Products";

            header("Location: /");
            exit();
        }

        public static function find_categoryid($data){
            global $db;
            $sql = "SELECT cetegory_id FROM `categories` WHERE `name`= ?";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                header("Location: /?status=Error!");
                exit();
            }
            $stmt->bind_param("s",$data);
            $stmt->execute();
            $result = $stmt->get_result();
            $id = $result->fetch_all(MYSQLI_ASSOC);
            return $id[0]["cetegory_id"];
            $stmt->close();
        }

        public static function image($data){
            //// Image
            $img_name = $data['image']['name'];
            
            $img_tmpname = $data["image"]["tmp_name"];
            //pathinfo() is a method that returns information about a file path
            $img_type = pathinfo($img_name,PATHINFO_EXTENSION);

            $type = array("jpg","svg","jpeg","png","gif");
            if(!in_array($img_type,$type)){
                echo "Please upload an image file";
                die();
            }
            /////////$_SERVER["DOCUMENT_ROOT"];
            move_uploaded_file($img_tmpname,$_SERVER["DOCUMENT_ROOT"]."../assets/img/".$img_name);
            $path_image = "/assets/img/".$img_name;
            return $path_image;
        }
        public static function Delete($id){
            global $db;
            $sql = "DELETE FROM `products` WHERE `products`.`product_id` = ?";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                header("Location: /?status=Error!");
                exit();
            }
            $stmt->bind_param("s",$id);
            $stmt->execute();
            $_SESSION["status"] = "Success to Delete";
            $stmt->close();
            header("Location: /");
        }

        public static function Edit($id, $newData){
            global $db;
            
            $name = $newData["product_name"];
            $price = $newData["price"];
            $desc = $newData["desc"];
            $sql = "UPDATE `products` SET `name`=?,`price`=?,`description`=? WHERE `products`.product_id = ?";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                $_SESSION["status"] = "Some thing got Error";
                header("Location: ".$_SERVER["HTTP_REFERER"]);
                exit();
            }
            $stmt->bind_param("sdsi",$name,$price,$desc,$id);
            if(!empty($_FILES["image"]["tmp_name"])){
                $img_path = Products::image($_FILES);
                $sql = "UPDATE `products` SET `name`=?,`price`=?,`description`=?,`image`=? WHERE `products`.`product_id` = ?";
                if(!$stmt->prepare($sql)){
                    $_SESSION["status"] = "Some thing got Error";
                    header("Location: ".$_SERVER["HTTP_REFERER"]);
                    exit();
                }
                $stmt->bind_param("sdssi",$name,$price,$desc,$img_path,$id);
            }
            $stmt->execute();
            $stmt->close();
            $db->close();
            header("Location: /views/partials/products_view.php?id=$id");
            exit();
        }
        public static function edt_image($id,$image){
            global $db;
            $img_path = Products::image($image);
            $sql = "UPDATE `products` SET `image`=? WHERE `products`.`product_id` = ?";
            $stmt = $db->stmt_init();
            if(!$stmt->prepare($sql)){
                $_SESSION["status"] = "Some thing got Error";
                header("Location: ".$_SERVER["HTTP_REFERER"]);
                exit();
            }
            $stmt->bind_param("si",$img_path,$id);
            $stmt->execute();
            $stmt->close();
            $db->close();
            header("Location: ".$_SERVER["HTTP_REFERER"]);
            exit();
        }
    }

    class Cart{
        public static function add($data){
            $id = $data["id"];
            $quantity = $data["quantity"];
            if(!isset($_SESSION["cart"])){
                $_SESSION["cart"][$id] = $quantity;
            }else{
                $_SESSION["cart"][$id] += $quantity;
            }
            echo "Success Add to the Cart";
        }

        public static function cancel($id){
            unset($_SESSION["cart"][$id." "]);
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
        public static function empty(){
            unset($_SESSION["cart"]);
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }


        public static function checkout($pid){
            global $db;
            date_default_timezone_set("Asia/Kuala_Lumpur");
            
            if(isset($_SESSION["cart"])){
                $user_id = $_SESSION["user_details"]["user_id"];
                $total = 0 ;
                $transaction_code = "TSC-".date("His")."-".mt_rand();
                $payment_id = $pid;
                $status_id = 1;

                $sql = "INSERT INTO `orders`( `total`,`transaction_code`, `user_id`, `status_id`, `payment_id`) VALUES (?,?,?,?,?)";
                $stmt = $db->stmt_init();
                if(!$stmt->prepare($sql)){
                    echo "Something Wrong!";
                    exit();
                }
                $stmt->bind_param("dsiii",$total,$transaction_code,$user_id,$status_id,$payment_id);
                $stmt->execute();
                
                $order_id = $db->insert_id;
                // this will return you the id of your last query;

                foreach($_SESSION["cart"] as $id=>$quantity){
                    $sql = "SELECT * FROM `products` WHERE product_id = ?";
                    if(!$stmt->prepare($sql)){
                        echo "Something Wrong!";
                        exit();
                    }
                    $stmt->bind_param("i",$id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $product = $result->fetch_assoc();

                    $total =+ ($product["price"]*$quantity);


                    $sql = "INSERT INTO `order_products`(`product_id`,`order_id`,`quantity`) VALUES(?,?,?)";
                    if(!$stmt->prepare($sql)){
                        echo "Something Wrong!";
                        exit();
                    }
                    $stmt->bind_param("iii",$id,$order_id,$quantity);
                    $stmt->execute();
                    
                }
                $sql = "UPDATE `orders` SET `total`= ? WHERE order_id = ?";
                if(!$stmt->prepare($sql)){
                    echo "Something Wrong!";
                    exit();
                }
                $stmt->bind_param("di",$total,$order_id);
                $stmt->execute();
                $stmt->close();
                $db->close();
                Cart::empty();
            }
        }
    }