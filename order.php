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
        $orderItem['quantity'] = $values['quantity'];
        $orderProducts[] = $orderItem;
    }
}

require 'header.php'

?>

    <div class="container">
        <h3><?= translate('order_id') ?> :<?= $order['id'] ?></h3>
        <h3><?= translate('order_client') ?> : <?= $order['user_name'] ?></h3>
        <h3><?= translate('order_price') ?> : <?= $order['total'] ?> $</h3>
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
                                        <li><?= translate('quantity') ?>: <?= $product['quantity'] ?> </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="index.php"><?= translate('navigate_index') ?></a>
                <?php if ($_SESSION['user_role'] = 1) : ?>
                     <a href="orders.php"><?= translate('navigate_orders') ?></a>
                <?php endif; ?>
    </div>
<?php require 'footer.php'; ?>
