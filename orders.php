<?php

include 'common.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    exit('You are not administrator');
}

$conn = connectDB();
$stmt = $conn->prepare('SELECT * FROM orders');
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';

?>
    <h1><?= translate('order') ?></h1>
    <a href="products.php"><?= translate('navigate_products') ?></a>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($orders as $order) : ?>
                <li>
                    <div class="proditem">
                        <div class="prodimage">
                            <?= $order['user_name'] ?>
                        </div>
                        <div class="proddetails">
                            <ul>
                                <li><?= translate('order_details') ?>: <?= $order['details'] ?></li>
                                <li><?= translate('order_price') ?>: <?= $order['total'] ?></li>
                                <li><?= translate('order_date') ?>: <?= $order['order_date'] ?> </li>
                            </ul>
                        </div>
                        <a href="order.php?id=<?= $order['id'] ?>" class="button"><?= translate('view') ?></a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php require 'footer.php'; ?>
