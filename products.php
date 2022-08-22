<?php

include 'common.php';

$lang = detectLanguage();
include "languages/$lang.php";

$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete'])) {
    $id = $_POST['product_id'];
    $sql = 'DELETE from products WHERE product_id=?';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt) {
        header("Location: products.php");
        die;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Products</title>
</head>

<body>
    <h1><?= translate('product') ?></h1>

    <div class="container">
        <ul class="proditems">
            <?php foreach ($result as $r) : ?>
                <li>

                    <div class="proditem">
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
                        <form action="products.php" method="POST">
                            <div class="removebutton">
                                <button type="submit" name="delete"><?= translate('delete') ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">

                            </div>
                        </form>
                        <form action="product.php?id=<?= $r['product_id'] ?>" method="POST">
                            <div class="editbutton">
                                <button type="submit" name="edit"><?= translate('edit') ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">

                            </div>
                        </form>

                    </div>

                </li>
            <?php endforeach; ?>



        </ul>
        <div class="addbutton">
            <a href="product.php"><?= translate('add_product') ?></a>
        </div>
    </div>
    <script>


    </script>
</body>

</html>
