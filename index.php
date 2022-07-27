<?php
include 'common.php';

$conn = connectDB();
$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
            <?php foreach ($result as $r): ?>
                <li>
                    <form action="index.php">
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
                            </div>

                        </div>
                    </form>
                </li>

            <?php endforeach; ?>
        </ul>
        <a href="cart.php">Go to cart</a>
    </div>
    <script>


    </script>
</body>

</html>