<?php
session_start();
$title = "Home";
function get_content()
{
    function get_data($where){
        include "controllers/connection.php";
        $sql = "SELECT * FROM $where";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }
    ?>
    <section>
        <header class="row justify-content-center align-items-center text-white mx-0">
            <div class="text-center ">
                <p class="lead font-weight-bold">Welcome to</p>
                <h1 class="font-weight-bold">B2 E-Commerce</h1>
                <div class="btn btn-success px-5">Let's Go</div>
            </div>
        </header>

        <?php if(isset($_SESSION["user_details"]) && $_SESSION["user_details"]["isAdmin"]){ ?> 
        <div class="container">
            <form action="/methods.php?action=addpost" method="post" class="py-5 col-md-6 mx-auto" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="product_name">Name</label>
                    <input type="text" id="product_name" name="product_name" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="price">Price</label>
                    <input type="number" name="price" id="price" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="image">image</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="ceetegory">Cetegory</label>
                    <select name="category" id="ceetegory" class="form-control">
                        <option value="none" disabled selected>Select Cetegory</option>
                        <?php 
                        $categories = get_data("categories");
                        foreach($categories as $categorie){ 
                        ?>
                            <option value="<?php echo $categorie["name"] ?> "><?php echo $categorie["name"] ?> </option>
                        <?php } ;
                        ?> 
                    </select>
                </div>
                <div class="mb-3">
                    <label for="desc">Description</label>
                    <textarea name="desc" id="desc" cols="30" rows="10" class="form-control"></textarea>
                </div>
                <input type="submit" value="Submit" class="d-block mx-auto btn btn-primary">
            </form>
        </div>
        <?php } ?> 

        <div class="container my-4">
            <div class="row">
                <?php 
                    $products = get_data("products");
                    foreach($products as $product){
                ?>
                    <div class="card mx-3 overx-hidden" style="width: 18rem;">
                        <img class="card-img-top" src="<?php echo $product["image"]; ?> " alt="Card image cap">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $product["name"]; ?></h5>
                            <p class="card-text"><?php echo $product["description"]; ?></p>
                            
                        </div>
                        <div class="card-footer row">
                            <a href="/methods.php?action=addcard&id=<?php echo $product["product_id"]?>" class="btn btn-primary">Add to Card</a>
                        <a href="/views/partials/products_view.php?id=<?php echo $product["product_id"] ?> " class="btn btn-warning">View</a>
                            <?php
                            if(isset($_SESSION["user_details"]) && $_SESSION["user_details"]["isAdmin"]){ 
                        ?> 
                            <a href="/methods.php?action=delproduct&id=<?php echo $product["product_id"]?>" class="btn btn-danger">Delete</a>
                        <?php } ?> 
                        </div>
                    </div>
                <?php
                    }
                ?> 
            </div>
        </div>
    </section>
<?php }
include "views/forms/layout.php";
?>