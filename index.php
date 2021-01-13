<?php
session_start();
$title = "Home";
function get_content()
{
    ?>
    <section>
        <header class="row justify-content-center align-items-center text-white mx-0">
            <div class="text-center ">
                <p class="lead font-weight-bold">Welcome to</p>
                <h1 class="font-weight-bold">B2 E-Commerce</h1>
                <div class="btn btn-success px-5">Let's Go</div>
            </div>
        </header>
    </section>
<?php }
include "views/forms/layout.php";
?>