<?php
include_once 'common.php';

if(!isset($_SESSION)) 
{ 
    session_start(); 
} 

$conn = connectDB();
$order_products = array();
if (isset($_SESSION['cart'])) {
    
    foreach ($_SESSION['cart'] as $key => $values) {

        $sql = "SELECT * from `products` WHERE product_id=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$values['product_id']]);
        $order_item = $stmt->fetch(PDO::FETCH_ASSOC);
       
    
        array_push($order_products, $order_item);

    }
}
?>
<html lang="en">


<body>
    <div>
        <div>Ordered by: {USER_NAME}</div>
        <?php foreach ($order_products as $r) : ?>
            <div class="proditem">
                <div class="prodimage">
                    <img style="width:50px; height:50px;" src="http://localhost/training/uploads/<?= $r['product_image'] ?>">
                </div>
                <div class="proddetails">
                    <ul>
                        <li>Title: <?= $r['title'] ?></li>
                        <li>Description: <?= $r['description'] ?></li>
                        <li>Price: <?= $r['price'] ?>$ </li>
                    </ul>
                </div>
                <?php endforeach; ?>
                <div class="order_details">
                    <p>Details: {ORDER_DETAILS}</p><br><br>
                    <p>Comments: {ORDER_COMMENTS}</p><br><br>
                    <p>Date: {ORDER_DATE}</p><br><br>
                </div>

            </div>
       
    </div>
</body>

</html>