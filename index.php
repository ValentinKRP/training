<?php
include 'common.php';

session_start();


$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        $product_ids = array_column($_SESSION['cart'], 'product_id');

        if (in_array($_POST['product_id'], $product_ids)) {
            echo "<script>alert('This product already in the cart!')</script>";
        } else {
            $count = count($_SESSION['cart']);
            $product = array(
                'product_id' => $_POST['product_id']
            );
            $_SESSION['cart'][$count] = $product;
            $product_ids = array_column($_SESSION['cart'], 'product_id');
        }
    } else {
        $product = array(
            'product_id' => $_POST['product_id']
        );
        $_SESSION['cart'][0] = $product;
        $product_ids = array_column($_SESSION['cart'], 'product_id');
    }
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
    <h1>PRODUCTS</h1>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($result as $r) : ?>
                <?php if (isset($_SESSION['cart']) && isset($_POST['add'])) : ?>
                    <?php if (!in_array($r['product_id'], $product_ids)) : ?>
                        <li>
                            <form action="index.php" method="POST">
                                <div class="proditem">
                                    <div class="prodimage">
                                        <img src="uploads/<?= $r['product_image'] ?>">
                                    </div>
                                    <div class="proddetails">
                                        <ul>
                                            <li>Title: <?= $r['title'] ?></li>
                                            <li>Description: <?= $r['description'] ?></li>
                                            <li>Price: <?= $r['price'] ?>$ </li>
                                        </ul>
                                    </div>
                                    <div class="addbutton">
                                        <button type="submit" name="add">ADD</button>
                                        <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                                    </div>

                                </div>
                            </form>
                        </li>
                    <?php endif; ?>

                <?php else : ?>
                    <li>
                        <form action="index.php" method="POST">
                            <div class="proditem">
                                <div class="prodimage">
                                    <img src="uploads/<?= $r['product_image'] ?>">
                                </div>
                                <div class="proddetails">
                                    <ul>
                                        <li>Title: <?= $r['title'] ?></li>
                                        <li>Description: <?= $r['description'] ?></li>
                                        <li>Price: <?= $r['price'] ?>$ </li>
                                    </ul>
                                </div>
                                <div class="addbutton">
                                    <button type="submit" name="add">ADD</button>
                                    <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                                </div>

                            </div>
                        </form>
                    </li>
                <?php endif; ?>

            <?php endforeach; ?>
        </ul>
        <a href="cart.php">Go to cart</a>
    </div>
    <script>


    </script>
</body>

</html>