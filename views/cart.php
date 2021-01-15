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
                            <td>$<?php echo $product["price"] ?></td>
                            <td><?php echo $quantity ?></td>
                            <td>$<?php echo number_format($product["price"]*$quantity , 2); $total += $product["price"]*$quantity; ?> </td>
                            <td><a href="/methods.php?action=cancelcart&id=<?php echo $id ?> "><i class="fas fa-times fa-1x "></i></a></td>
                        </tr>
                    <?php }?> 
                        <tr>
                            <td>
                                <a href="/methods.php?action=emptycart" class="btn btn-danger">Empty The Card</a>
                            </td>
                            <td>
                                <button class="btn btn-outline-success"
                                    data-toggle="modal" data-target="#checkout-modal"
                                >Checkout</button>
                                <div class="modal fade" id="checkout-modal">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Confirm Checkout</h5>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you really sure about your orders?</p>
                                                <strong>Total: $<?php echo number_format($total,2) ?></strong>
                                            </div>
                                            <div class="modal-footer"><input type="submit" value="Close" class="btn btn-secondary" data-dismiss="modal">
                                            <a href="/methods.php?action=checkoutcart&pid=1" class="btn btn-success">Checkout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td id="paypal-button-container">

                            </td>
                            <td>Total: $<?php echo number_format($total,2)?></td>
                        </tr>
                </tbody>
            </table>
        <?php }else{?>
        <h2>Your cart is empty</h2>
        <a href="/">Go back to shopping</a>
        <?php }?> 
    </div>
<?php
    }
    include "forms/layout.php";
?>