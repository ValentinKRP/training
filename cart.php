<?php

include 'common.php';

$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['cart'])) {
    $productIds = array_column($_SESSION['cart'], 'product_id');
    if (isset($_POST['remove'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value["product_id"] == $_POST['product_id']) {
                unset($_SESSION['cart'][$key]);
                unset($productIds[$key]);
                header("Location: cart.php");
                die;
            }
        }
    }

    if (isset($_POST['checkout'])) {
        if (!preg_match("/^[a-zA-Z-']*$/", testInput($_POST['username']))) {
            $usernameError = 'Enter a valid name';
        } else {
            $total = 0;
            $orderDate = date('Y-m-d h:i:sa');
            $sql = "SELECT * FROM `products` WHERE product_id=? ";
            $stmt = $conn->prepare($sql);

            foreach ($_SESSION['cart'] as $key => $values) {
                $stmt->execute([$values['product_id']]);
                $result = $stmt->fetch();
                $total = $total + $result['price'];
            }

            $sql = "INSERT INTO `orders` (user_name, details, price, order_date) VALUES(?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_POST['username'], $_POST['details'], $total, $orderDate]);
            $id = $conn->lastInsertId();

            $sql = "SELECT * FROM `products` WHERE product_id=? ";
            $stmt = $conn->prepare($sql);
            $sql2 = "INSERT INTO `users_orders` (order_id, product_id,product_price) VALUES(?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            foreach ($_SESSION['cart'] as $key => $values) {
                $stmt->execute([$values['product_id']]);
                $result = $stmt->fetch();
                $productId = $values['product_id'];
                $productPrice = $result['price'];
                $stmt2->execute([$id, $productId, $productPrice]);
            }

            sendEmail(testInput($_POST['username']), testInput($_POST['details']), testInput($_POST['comments']), $orderDate);

            unset($_SESSION['cart']);
            header("Location: index.php");
            die;
        }
    }
} else {
    $productIds = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Training</title>
</head>

<body>
    <h1><?= translate('cart_title') ?></h1>
    <div class="container">
        <div class="cartproducts">
            <div>
                <ul>
                    <?php foreach ($result as $r) : ?>
                        <?php if (in_array($r['product_id'], $productIds)) : ?>
                            <li>
                                <div class="proditemcart">
                                    <div class="prodimage">
                                        <img src="uploads/<?= $r['product_image'] ?>">
                                    </div>
                                    <div class="proddetails">
                                        <ul>
                                            <li><?= translate('product_title') ?>: <?= $r['title'] ?></li>
                                            <li><?= translate('product_description') ?>: <?= $r['description'] ?></li>
                                            <li><?= translate('product_price') ?>: <?= $r['price'] ?>$ </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <form action="cart.php" method="POST">
                                            <label><?= translate('quantity')?>:</label>
                                            <input type="number" name="quantity" value="1" min="1">
                                            <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                                        </form>
                                    </div>
                                    <div class="removebutton">
                                        <form action="cart.php" method="POST">
                                            <button type="submit" name="remove"><?= translate('remove') ?></button>
                                            <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                                        </form>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) : ?>
                <div class="inputs">
                    <form action="cart.php" method="POST">
                        <input type="text" name="username" placeholder="Name" required>
                        <br>
                        <?php if (isset($usernameError)) : ?>
                            <span style="color:red;"><?= $usernameError ?></span>
                            <br>
                        <?php endif; ?>
                        <input type="text" name="details" size="50" placeholder="Contact details" required>
                        <br>
                        <input type="text" name="comments" size="50" placeholder="Comments" required>
                        <br>
                        <button type="submit" name="checkout"><?= translate('checkout') ?></button>
                    </form>
                </div>
            <?php else : ?>
                <div><?= translate('empty_cart') ?></div>
            <?php endif ?>
            <a href="index.php"><?= translate('index') ?></a>
        </div>
    </div>
</body>

</html>
