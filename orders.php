<?php

include 'common.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    exit('You are not administrator');
}

$conn = connectDB();
$stmt = $conn->prepare('SELECT * FROM orders');
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <h1><?= translate('order') ?></h1>
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
                                <li><?= translate('order_price') ?>: <?= $order['price'] ?></li>
                                <li><?= translate('order_date') ?>: <?= $order['order_date'] ?> </li>
                            </ul>
                        </div>
                        <form action="order.php?id=<?= $order['id'] ?>" method="POST">
                            <div class="viewbutton">
                                <button type="submit" name="view"><?= translate('view') ?></button>
                                <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            </div>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>

</html>
