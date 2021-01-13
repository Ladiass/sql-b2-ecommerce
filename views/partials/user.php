<?php 
    $title = "User";
    session_start();
    if(isset($_SESSION["user_details"])){
        header("Location: /");
        exit();
    }
    function get_content(){?>
    <section>
        <link rel="stylesheet" href="/assets/css/log_reg.css">
        <div class="container">
            <div class="row justify-content-center align-items-center m-5 " >
                <div class="login col-6 px-5">
                    <form action="/methods.php?action=login" method="post" class="form-group">
                        <h4 class="text-center">Login</h4>
                        <label for="lusername">Username</label>
                        <input type="text" name="username" id="lusername" class="form-control d-block" 
                        <?php if(isset($_GET["username"])){?>
                            value="<?php echo $_GET["username"];?>"
                        <?php } ?> 
                        >
                        <label for="lpass">Password</label>
                        <input type="password" name="password" id="lpass" class="form-control ">
                        <input type="submit" value="Login" class="btn btn-success my-2 d-block mx-auto px-5">
                    </form>
                </div>
                <div class="register col-6 px-5">
                    <form action="/methods.php?action=register" method="post" class="form-group">
                        <h4 class="text-center">Register</h4>
                        <label for="rusername">Username</label>
                        <input type="text" name="username" id="rusername" class="form-control"
                        <?php if(isset($_GET["rusername"])){?>
                            value="<?php echo $_GET["rusername"];?>"
                        <?php } ?>
                        >
                        <label for="remail">Email</label>
                        <input type="email" name="email" id="remail" class="form-control">

                        <label for="f_name">FristName</label>
                        <input type="text" name="fristname" id="f_name" class="form-control">

                        <label for="l_name">lastName</label>
                        <input type="text" name="lastname" id="l_name" class="form-control">

                        <label for="addr">Address</label>
                        <input type="text" name="address" id="addr" class="form-control">

                        <label for="rpass">Password</label>
                        <input type="password" name="password" id="rpass" class="form-control ">
                        <label for="rpass2">Password 2</label>
                        <input type="password" name="password2" id="rpass2" class="form-control ">

                        <input type="submit" value="Register" class="btn btn-success my-2 d-block mx-auto px-5">
                    </form>
                </div>
            </div>
        </div>
    </section>
<?php        
    };
    include "../forms/layout.php";
?> 