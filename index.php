<?php

include 'common.php';

$conn = connectDB();

if (isset($_SESSION['cart']) && count($_SESSION['cart']) !== 0) {
    $productIds = array_column($_SESSION['cart'], 'product_id');
    $result = [];
    $excludeIds = array_values($productIds);
    $count = count($excludeIds);
    for ($count; $count > 0; $count--) {
        $in[] = '?';
    }

    $in = implode(', ', $in);

    $sql = "SELECT * from products WHERE product_id NOT IN ($in)";
    $stmt = $conn->prepare($sql);
    $stmt->execute($excludeIds);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $productIds = [];
    $stmt = $conn->prepare('SELECT * from products');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (
    isset($_POST['add'])
    && isset($_SESSION['cart'])
    && !(in_array($_POST['product_id'], $productIds))
) {
            $count = count($_SESSION['cart']);
            $product = [
                'product_id' => $_POST['product_id'],
            ];
            $_SESSION['cart'][] = $product;
            $productIds[] = $_POST['product_id'];

            header("Location: index.php");
            die;
} elseif (
    isset($_POST['add'])
          && !isset($_SESSION['cart'])
) {
        $product = [
            'product_id' => $_POST['product_id'],
        ];
        $_SESSION['cart'] = [];
        $_SESSION['cart'][] = $product;
        $productIds[] = $_POST['product_id'];
        header("Location: index.php");
        die;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title><?= translate('title')?></title>
</head>

<body>
    <h1><?= translate('title') ?></h1>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($result as $r) : ?>
                <li>
                    <form action="index.php" method="POST">
                        <div class="proditem">
                            <div class="prodimage">
                                <img src="uploads/<?= $r['product_image'] ?>">
                            </div>
                            <div class="proddetails">
                                <ul>
                                    <li><?= translate('product_title') ?>: <?= $r['title'] ?></li>
                                    <li><?= translate('product_description')?>: <?= $r['description'] ?></li>
                                    <li><?= translate('product_price')?>: <?= $r['price'] ?>$ </li>
                                </ul>
                            </div>
                            <div class="addbutton">
                                <button type="submit" name="add"><?=translate('add') ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                            </div>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="cart.php"><?=translate('cart') ?></a>
    </div>
</body>

</html>
