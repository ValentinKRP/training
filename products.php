<?php

include 'common.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
     exit('You are not administrator');
}


$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete'])) {
    $id = $_POST['product_id'];
    $sql = 'DELETE FROM products WHERE id=?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt) {
        header('Location: products.php');
        die;
    }
}

require 'header.php';

?>

    <h1><?= translate('product') ?></h1>

    <div class="container">
        <ul class="proditems">
            <?php foreach ($products as $product) : ?>
                <li>

                    <div class="proditem">
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
                        <form action="products.php" method="POST">
                            <div class="removebutton">
                                <button type="submit" name="delete"><?= translate('delete') ?></button>
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            </div>
                        </form>
                        <a href="product.php?id=<?= $product['id'] ?>" class="button"><?= translate('edit') ?></a>

                    </div>

                </li>
            <?php endforeach; ?>



        </ul>
        <div class="addbutton">
            <a href="product.php"><?= translate('add_product') ?></a>
        </div>
        <div class="addbutton">
            <a href="orders.php"><?= translate('navigate_orders') ?></a>
        </div>
        <div class="addbutton">
            <a href="index.php"><?= translate('navigate_index') ?></a>
        </div>
    </div>
    <script>


    </script>
<?php require 'footer.php'; ?>
