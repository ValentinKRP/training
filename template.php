<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div>
    <div>Ordered by: {USER_NAME}</div>
    <!-- <?php foreach ($result as $r) : ?> -->
        <div class="proditem">
            <div class="prodimage">
                <!-- <img src="uploads/<?= $r['product_image'] ?>"> -->
            </div>
            <div class="proddetails">
                <!-- <ul>
                    <li><?= $lang['product_title'] ?>: <?= $r['title'] ?></li>
                    <li><?= $lang['product_description'] ?>: <?= $r['description'] ?></li>
                    <li><?= $lang['product_price'] ?>: <?= $r['price'] ?>$ </li>
                </ul> -->
            </div>
            <div class="order_details">
                <p>Details: {ORDER_DETAILS}</p><br><br>
                <p>Comments: {ORDER_COMMENTS}</p><br><br>
            </div>

        </div>
    <?php endforeach; ?>
    </div>
</body>

</html>