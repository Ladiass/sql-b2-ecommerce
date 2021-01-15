<?php 
    session_start();
    include "../../controllers/connection.php";
    if(!isset($_GET["id"])){
        header("Location: /views/forms/missing-page.php");
        exit();
    }
    
    function get_content(){
        global $db;
        $id = $_GET["id"]; 
        $sql = "SELECT * FROM `products` WHERE `products`.`product_id` = ?";
        $stmt = $db->stmt_init();
        if(!$stmt->prepare($sql)){
            $_SESSION["status"] = "Some thing was missing !";
            header("Location: /?test");
            exit();
        }
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_all(MYSQLI_ASSOC)[0];
        if(empty($product)){
            header("Location: /views/forms/missing-page.php");
            exit();
        }
?>
    <section>
        <div class="container-fluid">
            <div class="row">
                <img src="<?php echo $product["image"] ?> " alt=""class="col-12 col-md-4 ">
                <div class="col-12 col-md-8 py-5 mt-5 pl-5">
                    <h3><?php echo $product["name"] ?></h3>
                    <p class="lead font-weight-bold text-secondary mt-3">RM:  <?php echo $product["price"]?></p>
                    <p>
                        <?php echo $product["description"] ?> 
                    </p>
                    <div class="row gap-2">
                        <?php if(isset($_SESSION["user_details"]) && $_SESSION["user_details"]["isAdmin"]){ ?> 
                            <a href="/views/partials/product_edit.php?action=edit&id=<?php echo $id ?> " class="btn btn-warning">Edit</a>
                            <a href="/methods.php?action=delproduct&id=<?php echo $id ?> " class="btn btn-danger">Delete</a>
                        <?php } ?> 

                        <div class="input-group">
                                <input type="number" name="quantity" class="" min="1">
                                <input type="submit" class="btn btn-outline-success addcard" data-id="<?php echo $product["product_id"] ?> " value="Add to Card">
                        </div>
                        
                        <a href="<?php
                            if(isset($_SESSION["user_details"])){
                                echo "/methods.php?action=buyproduct&id=$id";
                            }else{
                                echo "#";
                                $_SESSION["status"] = "Pls login";
                            }
                        ?>"  class="btn btn-success">Buy One</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let btn = document.querySelectorAll(".addcard")
        btn.forEach((e , i) => {
            e.addEventListener("click",()=>{
                let id = e.getAttribute("data-id");
                let quantity = e.previousElementSibling.value;
                if(quantity.length !== 0){
                    let formBody = new FormData;        

                    formBody.append('id',id);
                    formBody.append('quantity',quantity);

                    fetch("/methods.php?action=addcard",{
                        method : "POST",
                        body : formBody
                    }).then(
                        res=>console.log(res.text())
                    ).then(
                        data=>{
                            let cart = document.getElementById("cart_count");
                            if(parseInt(cart.innerHTML) != ""){
                                cart.innerHTML= parseInt(cart.innerHTML) + parseInt(quantity);
                            }else{
                                cart.innerHTML = parseInt(quantity);
                            }
                        }
                    )
                }
            })
        });
    </script>

<?php
    }
    include "../forms/layout.php";
?> 