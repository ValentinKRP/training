<?php

include 'common.php';

$conn = connectDB();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = 'SELECT * FROM `orders` WHERE id=? ';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = 'SELECT * FROM `users_orders` WHERE order_id=? ';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $orderProducts = [];
    $orderItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($orderItems as $key => $values) {
        $sql = 'SELECT * FROM `products` WHERE id=? ';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$values['product_id']]);
        $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
        $orderItem['price'] = $values['product_price'];
        $orderProducts[] = $orderItem;
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
    <title><?= translate('title') ?></title>

</head>

<body>
    <div class="container">
        <h3><?= translate('order_id') ?> :<?= $order['id'] ?></h1>
            <h3><?= translate('order_client') ?> : <?= $order['user_name'] ?></h1>
                <ul class="proditems">
                    <?php foreach ($orderProducts as $product) : ?>
                        <li>
                            <div class="proditem">
                                <div class="prodimage">
                                    <img src="uploads/<?= $product['product_image'] ?>">
                                </div>
                                <div class="proddetails">
                                    <ul>
                                        <li><?= translate('product_title') ?>: <?= $product['title'] ?></li>
                                        <li><?= translate('product_description') ?>: <?= $product['description'] ?></li>
                                        <li><?= translate('product_price') ?>: <?= $product['price'] ?>$ </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="orders.php"><?= translate('navigate_orders') ?></a>
    </div>
</body>

</html>
