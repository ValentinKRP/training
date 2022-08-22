<?php

include 'common.php';

$lang = detectLanguage();
include "languages/$lang.php";

$conn = connectDB();
$stmt = $conn->prepare('SELECT * from orders');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Orders</title>
</head>

<body>
    <h1><?= translate('order') ?></h1>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($result as $r) : ?>
                <li>
                    <div class="proditem">
                        <div class="prodimage">
                            <?= $r['user_name'] ?>
                        </div>
                        <div class="proddetails">
                            <ul>
                                <li><?= translate('order_details') ?>: <?= $r['details'] ?></li>
                                <li><?= translate('order_price') ?>: <?= $r['price'] ?></li>
                                <li><?= translate('order_date') ?>: <?= $r['order_date'] ?> </li>
                            </ul>
                        </div>
                        <form action="order.php?id=<?= $r['order_id'] ?>" method="POST">
                            <div class="viewbutton">
                                <button type="submit" name="view"><?= translate('view') ?></button>
                                <input type="hidden" name="order_id" value="<?= $r['order_id'] ?>">
                            </div>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

</body>

</html>
