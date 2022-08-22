<?php

include_once 'common.php';

$conn = connectDB();
$orderProducts = [];
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $values) {
        $sql = "SELECT * from `products` WHERE product_id=? ";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$values['product_id']]);
        $orderItem = $stmt->fetch(PDO::FETCH_ASSOC);
        $orderProducts = $orderItem;
    }
}
?>
<html lang="en">


<body>
    <div>
        <div> <?= translate('order_by') ?>: {USER_NAME}</div>
        <?php foreach ($orderProducts as $r) : ?>
            <div class="proditem">
                <div class="prodimage">
                    <img style="width:50px; height:50px;" src="http://localhost/training/uploads/<?= $r['product_image'] ?>">
                </div>
                <div class="proddetails">
                    <ul>
                        <li><?= translate('product_title') ?>: <?= $r['title'] ?></li>
                        <li><?= translate('product_description') ?>: <?= $r['description'] ?></li>
                        <li><?= translate('product_price') ?>: <?= $r['price'] ?>$ </li>
                    </ul>
                </div>
        <?php endforeach; ?>
            <div class="order_details">
                <p><?= translate('order_details') ?>: {ORDER_DETAILS}</p><br><br>
                <p><?= translate('order_comments') ?>: {ORDER_COMMENTS}</p><br><br>
                <p><?= translate('order_date') ?>: {ORDER_DATE}</p><br><br>
            </div>

            </div>

    </div>
</body>

</html>
