<?php 
    session_start();
    include "../../controllers/connection.php";
    if(!isset($_GET["id"]) && !$_SESSION["user_details"]["isAdmin"]){
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
            <form action="/methods.php?action=editproduct&id=<?php echo $id ?>" method="POST" class="row" enctype="multipart/form-data">
                <label for="image" class="col-12 col-md-4"><img src="<?php echo $product["image"] ?>" alt="" width="100%"></label>
                <input type="file" name="image" class="d-none" id="image">
                <div class="col-12 col-md-8 py-5 mt-5 pl-5">
                    <label for="product_name">Product Name :</label>
                    <h3>
                        <input type="text" value="<?php echo $product["name"]?>" class="form-control font-weight-bold text-dark" id="product_name" name="product_name">
                    </h3>
                    <p class="lead text-secondary mt-3">
                    <label for="price">RM:  </label>
                        <input type="text" value="<?php echo $product["price"]?>" class="form-control font-weight-bold text-dark" name="price" id="price">
                    </p>
                    <p>
                    <label for="desc">Description :</label>
                        <textarea name="desc" id="desc" cols="30" rows="10" class="form-control font-weight-bold text-dark"><?php echo $product["description"] ?> </textarea>
                    </p>
                    <div class="row gap-2 justify-content-center">
                        <a href="/views/partials/products_view.php?id=<?php echo $id ?>" class="btn btn-warning">Cancel</a>
                        <input type="submit" value="Done" class="btn btn-primary">
                    </div>
                </div>
            </form>
        </div>
    </section>

<?php
    }
    include "../forms/layout.php";
?> 