<?php
include 'common.php';

session_start();

$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['cart'])) {

    $product_ids = array_column($_SESSION['cart'], 'product_id');
    if (isset($_POST['remove'])) {

        foreach ($_SESSION['cart'] as $key => $value) {

            if ($value["product_id"] == $_POST['product_id']) {
                unset($_SESSION['cart'][$key]);
                unset($product_ids[$key]);
            }
        }
    }
    $product_ids = array_column($_SESSION['cart'], 'product_id');

    if (isset($_POST['quantity'])) {
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($value['product_id'] == $_POST['product_id']) {
                $_SESSION['cart'][$key]['quantity'] = $_POST['quantity'];
                print_r($_SESSION['cart']);
            }
        }
    }

    if (isset($_POST['checkout'])) {
        $total = 0;
        $sql = "SELECT * FROM `products` WHERE product_id=? ";
        $stmt = $conn->prepare($sql);
        foreach($_SESSION['cart'] as $key => $values){
            print_r($values['product_id']);
               $stmt->execute([$values['product_id']]);
               $result = $stmt->fetch();
               $total=$total + ($result['price']*$values['quantity']);

        }
        print_r($total);
        $sql = "INSERT INTO `orders` (user_name, details, price) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['username'], $_POST['details'], $total]);
        $id = $conn->lastInsertId();
        $sql2 = "INSERT INTO `users_orders` (order_id, product_id) VALUES(?, ?)";
        $stmt = $conn->prepare($sql2);
        foreach ($_SESSION['cart'] as $key => $values) {
            $product_id = $values['product_id'];
            $stmt->execute([$id, $product_id]);
        }
        unset($_SESSION['cart']);
        echo "<script>alert('Order placed')
        window.location.href='index.php'</script>";
    }
} else {
    $product_ids = array();
}
print_r($product_ids);



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
    <h1>CART</h1>
    <div class="container">
        <div class="cartproducts">

            <div>
                <ul>
                    <?php foreach ($result as $r) : ?>
                        <?php if (in_array($r['product_id'], $product_ids)) : ?>
                            <li>
                                <div class="proditemcart">
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
                                    <div>
                                        <form action="cart.php" method="POST">
                                            <label>Quantity</label>
                                            <?php foreach ($_SESSION['cart'] as $key => $value) : ?>
                                                <?php if ($value['product_id'] == $r['product_id']) : ?>
                                                    <input type="number" name="quantity" value="<?= $value['quantity'] ?>" min="1">
                                                    <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">

                                                <?php endif; ?>

                                            <?php endforeach; ?>
                                        </form>

                                    </div>
                                    <div class="removebutton">
                                        <form action="cart.php" method="POST">
                                            <button type="submit" name="remove">REMOVE</button>
                                            <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">
                                        </form>
                                    </div>

                                </div>


                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>

                </ul>
            </div>
            <div class="inputs">
                <form action="cart.php" method="POST">
                    <input type="text" name="username" placeholder="Name" required>
                    <br>
                    <input type="text" name="details" size="50" placeholder="Contact details" required>
                    <br>
                    <input type="text" name="comments" size="50" placeholder="Comments" required>
                    <br>
                    <input type="hidden">
                    <a href="index.php">Go to index</a>
                    <button type="submit" name="checkout">checkout</button>
                </form>
            </div>

        </div>
    </div>

</body>

</html>