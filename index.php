<?php

include 'common.php';

session_start();


$lang = translate();
include "languages/$lang.php";

$conn = connectDB();



if (isset($_SESSION['cart']) && count($_SESSION['cart']) !== 0) {
    $product_ids = array_column($_SESSION['cart'], 'product_id');
    $result = array();

    $exclude_ids = array_values($product_ids);

    $in = implode(',', array_map('intval', $exclude_ids));

    $sql = "SELECT * from products WHERE product_id NOT IN ($in)";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $product_ids = array();
    $stmt = $conn->prepare('SELECT * from products');
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        if (!(in_array($_POST['product_id'], $product_ids))) {
            $count = count($_SESSION['cart']);
            $product = array(
                'product_id' => $_POST['product_id']

            );
            array_push($_SESSION['cart'], $product);
            array_push($product_ids, $_POST['product_id']);
        } else {
            header("Location: index.php");
            die;
        }
    } else {
        $product = array(
            'product_id' => $_POST['product_id'],

        );
        $_SESSION['cart'][0] = $product;
        array_push($product_ids, $_POST['product_id']);
    }
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
    <title><?= $lang['title'] ?></title>
</head>

<body>
    <h1><?= $lang['title'] ?></h1>
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
                                    <li><?= $lang['product_title'] ?>: <?= $r['title'] ?></li>
                                    <li><?= $lang['product_description'] ?>: <?= $r['description'] ?></li>
                                    <li><?= $lang['product_price'] ?>: <?= $r['price'] ?>$ </li>
                                </ul>
                            </div>
                            <div class="addbutton">
                                <button type="submit" name="add"><?= $lang['add'] ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                            </div>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="cart.php"><?= $lang['cart'] ?></a>
    </div>
            </body>
            
</html>