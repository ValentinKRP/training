<?php

include_once 'common.php';

$conn = connectDB();
$orderProducts = [];
if (isset($_SESSION['cart'])) {
        $productIds = array_keys($_SESSION['cart']);
        $products = [];
        $excludeIds = array_values($productIds);
        $count = count($excludeIds);
        $in = [];
    for ($count; $count > 0; $count--) {
            $in[] = '?';
    }
        $in = implode(', ', $in);
        $conn = connectDB();
        $sql = 'SELECT * FROM products WHERE id IN (' . $in . ')';
        $stmt = $conn->prepare($sql);
        $stmt->execute($excludeIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<html lang="en">


<body>
    <div>
        <div> <?= translate('order_by') ?>: {USER_NAME}</div>
        <?php foreach ($products as $product) : ?>
            <div class="proditem">
                <div class="prodimage">
                    <img style="width:50px; height:50px;" src="http://localhost/training/uploads/<?= $product['product_image'] ?>">
                </div>
                <div class="proddetails">
                    <ul>
                        <li><?= translate('product_title') ?>: <?= $product['title'] ?></li>
                        <li><?= translate('product_description') ?>: <?= $product['description'] ?></li>
                        <li><?= translate('product_price') ?>: <?= $product['price'] ?>$ </li>
                        <li><?= translate('quantity') ?>: <?= $_SESSION['cart'][$product['id']]?></li>
                    </ul>
                </div>
        <?php endforeach; ?>
            <div class="order_details">
                <p><?= translate('order_price') ?>: {ORDER_TOTAL}</p><br><br>
                <p><?= translate('order_details') ?>: {ORDER_DETAILS}</p><br><br>
                <p><?= translate('order_comments') ?>: {ORDER_COMMENTS}</p><br><br>
                <p><?= translate('order_date') ?>: {ORDER_DATE}</p><br><br>
            </div>

            </div>

    </div>
</body>

</html>
