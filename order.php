<?php

include 'common.php';

$lang = translate();
include "languages/$lang.php";
$conn = connectDB();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * from `orders` WHERE order_id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = "SELECT * from `users_orders` WHERE order_id=? ";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $order_products = array();
    $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($order_items as $key => $values) {
        $sql = "SELECT * from `products` WHERE product_id=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$values['product_id']]);
        $order_item = $stmt->fetch(PDO::FETCH_ASSOC);
        $order_item['price'] = $values['product_price'];
        array_push($order_products, $order_item);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Order</title>

</head>

<body>
    <div class="container">
        <h3><?= $lang['order_id'] ?> :<?= $order['order_id'] ?></h1>
            <h3><?= $lang['order_client'] ?> : <?= $order['user_name'] ?></h1>
                <ul class="proditems">
                    <?php foreach ($order_products as $r) : ?>
                        <li>
                            <div class="proditem">
                                <div class="prodimage">
                                    <img src="uploads/<?= $r['product_image'] ?>">
                                </div>
                                <div class="proddetails">
                                    <ul>
                                        <li><?= $lang['product_title'] ?>: <?= $r['title'] ?></li>
                                        <li><?= $lang['product_description'] ?>: <?= $r['description'] ?></li>
                                        <li><?= $lang['product_price'] ?>: <?= $r['price'] ?>$ </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="orders.php"><?= $lang['navigate_orders'] ?></a>
    </div>
</body>

</html>