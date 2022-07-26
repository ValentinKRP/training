<?php

include 'common.php';

$conn = connectDB();

if (isset($_SESSION['cart'])) {
    if (count($_SESSION['cart']) !== 0) {
        $productIds = array_keys($_SESSION['cart']);
        $products = [];
        $excludeIds = array_values($productIds);
        $count = count($excludeIds);
        for ($count; $count > 0; $count--) {
            $in[] = '?';
        }

        $in = implode(', ', $in);

        $sql = 'SELECT * FROM products WHERE id NOT IN (' . $in . ')';
        $stmt = $conn->prepare($sql);
        $stmt->execute($excludeIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $productIds = [];
        $stmt = $conn->prepare('SELECT * FROM products');
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    if (
        isset($_POST['add'])
        && !in_array($_POST['product_id'], $productIds)
    ) {
            $count = count($_SESSION['cart']);
            $quantity = 1;
            $_SESSION['cart'][$_POST['product_id']] = $quantity;
            header('Location: index.php');
            die;
    }
} else {
    $stmt = $conn->prepare('SELECT * from products');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['add'])) {
            $quantity = 1;
            $_SESSION['cart'] = [];
            $_SESSION['cart'][$_POST['product_id']] = $quantity;
            header('Location: index.php');
            die;
    }
}


require 'header.php';
?>

    <h1><?= translate('title') ?></h1>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($products as $product) : ?>
                <li>
                    <form action="index.php" method="POST">
                        <div class="proditem">
                            <div class="prodimage">
                                <img src="uploads/<?= $product['product_image'] ?>">
                            </div>
                            <div class="proddetails">
                                <ul>
                                    <li><?= translate('product_title') ?>: <?= $product['title'] ?></li>
                                    <li><?= translate('product_description')?>: <?= $product['description'] ?></li>
                                    <li><?= translate('product_price')?>: <?= $product['price'] ?>$ </li>
                                </ul>
                            </div>
                            <div class="addbutton">
                                <button type="submit" name="add"><?=translate('add') ?></button>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            </div>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="cart.php"><?= translate('cart') ?></a>
        <a href="login.php"><?= translate('login') ?></a>
        <?php if ($_SESSION['user_role'] = 1) : ?>
            <a href="products.php"><?= translate('navigate_products') ?></a>
        <?php endif; ?>
    </div>
<?php require 'footer.php' ?>