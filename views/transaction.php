<?php

use function Composer\Autoload\includeFile;

session_start();
    if(!isset($_SESSION["user_details"]) && !$_SESSION["user_details"]["isAdmin"]){
        header("Location: ".$_SERVER["REFERER"]);
    };
    function get_content(){
    include "../controllers/connection.php";
    $sql = "SELECT * FROM `orders`;";
    $stmt = $db->stmt_init();
    if(!$stmt->prepare($sql)){
        echo "Some thing error";
        exit();
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
?>
    <section>
        <div class="container">
            <table class="table">
                <thead>
                    <tr>
                        <th>index</th>
                        <th>order id</th>
                        <th>total</th>
                        <th>transaction code</th>
                        <th>purchase date</th>
                        <th>user id</th>
                        <th>payment id</th>
                        <th>status id</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php     
                    foreach($data as $i=>$transaction){ ?>
                        <tr>
                            <td><?php echo $i+1 ?></td>
                            <td><?php echo $transaction["order_id"] ?></td>
                            <td><?php echo $transaction["total"] ?></td>
                            <td><?php echo $transaction["transaction_code"] ?></td>
                            <td><?php echo $transaction["purchase_date"] ?></td>
                            <td><?php echo $transaction["user_id"] ?></td>
                            <td><?php echo $transaction["payment_id"] ?></td>
                            <td><?php echo $transaction["status_id"] ?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>
    


<?php 
    }
    include "forms/layout.php";
?>