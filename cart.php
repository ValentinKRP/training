<?php

include 'common.php';



if (isset($_SESSION['cart'])) {
    if (count($_SESSION['cart']) !== 0) {
        $productIds = array_column($_SESSION['cart'], 'product_id');
        $products = [];
        $excludeIds = array_values($productIds);
        $count = count($excludeIds);
        for ($count; $count > 0; $count--) {
            $in[] = '?';
        }
        $in = implode(', ', $in);
        $conn = connectDB();
        $sql = "SELECT * FROM products WHERE id IN ($in)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($excludeIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $products = [];
    }

    if (isset($_POST['remove'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['product_id'] === $_POST['product_id']) {
                unset($_SESSION['cart'][$key]);
                unset($productIds[$key]);
                header('Location: cart.php');
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
            $sql = 'SELECT * FROM `products` WHERE id=? ';
            $stmt = $conn->prepare($sql);

            foreach ($products as $product) {
                $total = $total + $product['price'];
            }

            $sql = 'INSERT INTO `orders` (user_name, details, comments, total, order_date) VALUES(?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_POST['username'], $_POST['details'], $_POST['comments'], $total, $orderDate]);
            $id = $conn->lastInsertId();

            $sql2 = 'INSERT INTO `users_orders` (order_id, product_id,product_price) VALUES(?, ?, ?)';
            $stmt2 = $conn->prepare($sql2);
            foreach ($products as $product) {
                $productId = $product['id'];
                $productPrice = $product['price'];
                $stmt2->execute([$id, $productId, $productPrice]);
            }

            $emailTo = SHOP_MANAGER;
            $subject = 'New order placed';
            $headers = "From: demo mail <valentin.carp15@gmail.com>\r\n";
            $headers .= "MIME-Version:1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $swapVar = [
                "{ORDER_DETAILS}" => testInput($_POST['details']),
                "{ORDER_COMMENTS}" => testInput($_POST['comments']),
                "{USER_NAME}" => testInput($_POST['username']),
                "{ORDER_DATE}" => $orderDate
            ];

            ob_start();
            include 'template.php';
            $message = ob_get_clean();

            foreach (array_keys($swapVar) as $key) {
                if (strlen($key) > 2 && trim($key) != "") {
                    $message = str_replace($key, $swapVar[$key], $message);
                }
            }

             mail($emailTo, $subject, $message, $headers);

            unset($_SESSION['cart']);
            header('Location: index.php');
            die;
        }
    }
} else {
    $products = [];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title><?= translate('title') ?></title>
</head>

<body>
    <h1><?= translate('cart_title') ?></h1>
    <div class="container">
        <div class="cartproducts">
            <div>
                <ul>
                    <?php foreach ($products as $product) : ?>
                            <li>
                                <div class="proditemcart">
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
                                    <div class="removebutton">
                                        <form action="cart.php" method="POST">
                                            <button type="submit" name="remove"><?= translate('remove') ?></button>
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        </form>
                                    </div>
                                </div>
                            </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) != 0) : ?>
                <div class="inputs">
                    <form action="cart.php" method="POST">
                        <input type="text" name="username" placeholder="<?= translate('order_client') ?>" required>
                        <br>
                        <?php if (isset($usernameError)) : ?>
                            <span style="color:red;"><?=  translate('username_error') ?></span>
                            <br>
                        <?php endif; ?>
                        <input type="text" name="details" size="50" placeholder="<?= translate('order_details') ?>" required>
                        <br>
                        <input type="text" name="comments" size="50" placeholder="<?= translate('order_comments') ?>">
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
