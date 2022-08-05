<?php
include 'common.php';

session_start();

$lang = translate();
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
    } else {
       
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
    <h1><?= $lang['product'] ?></h1>

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
                                <li><?= $lang['product_title'] ?>: <?= $r['title'] ?></li>
                                <li><?= $lang['product_description'] ?>: <?= $r['description'] ?></li>
                                <li><?= $lang['product_price'] ?>: <?= $r['price'] ?>$ </li>
                            </ul>
                        </div>
                        <form action="products.php" method="POST">
                            <div class="removebutton">
                                <button type="submit" name="delete"><?= $lang['delete'] ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">

                            </div>
                        </form>
                        <form action="product.php?id=<?=$r['product_id']?>" method="POST">
                            <div class="editbutton">
                                <button type="submit" name="edit"><?= $lang['edit'] ?></button>
                                <input type="hidden" name="product_id" value="<?= $r['product_id'] ?>">

                            </div>
                        </form>

                    </div>

                </li>
                <?php endforeach; ?>


           
        </ul>
        <div class="addbutton">
            <a href="product.php"><?= $lang['add_product'] ?></a>
        </div>
    </div>
    <script>


    </script>
</body>

</html>