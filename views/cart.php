<?php 
    session_start();
    function get_content(){
    include "../controllers/connection.php";        
?>

    <div class="container py-5">
        <?php if(isset($_SESSION["cart"])&&count($_SESSION["cart"])){ ?> 
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <?php $total = 0;
                        foreach($_SESSION["cart"] as $id => $quantity){
                        $sql = "SELECT * FROM `products` WHERE `products`.`product_id`= ? ";
                        $stmt = $db->stmt_init();
                        if(!$stmt->prepare($sql)){
                            echo "Error";
                            exit();
                        }
                        $stmt->bind_param('i',$id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $product = $result->fetch_all(MYSQLI_ASSOC)[0];

                    ?>
                        <tr>
                            <td><?php echo $product["name"] ?> </td>
                            <td>RM <?php echo $product["price"] ?></td>
                            <td><?php echo $quantity ?></td>
                            <td>RM <?php echo number_format($product["price"]*$quantity , 2) ?> </td>
                            <td><a href="/methods.php?action=cancelcart&id=<?php echo $id ?> "><i class="fas fa-times fa-1x "></i></a></td>
                        </tr>
                    <?php }?> 
                </tbody>
            </table>
        <?php }else{?>
        <h2>Your cart is empty</h2>
        <a href="/">Go back to shopping</a>
        <?php
        } ?> 
    </div>
<?php
    }
    include "forms/layout.php";
?> 