<?php
include 'common.php';

session_start();

if (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = 'en';
} else if (isset($_GET['lang']) && $_SESSION['lang'] != $_GET['lang']  && !empty($_GET['lang'])) {
    if ($_GET['lang'] == 'en') {
        $_SESSION['lang'] = 'en';
    } else if ($_GET['lang'] == 'ro') {
        $_SESSION['lang'] = 'ro';
    }
}

require_once "languages/" . $_SESSION['lang'] . ".php";

$conn = connectDB();

$user_data=check_login($conn);

$stmt = $conn->prepare('SELECT * from products');
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (isset($_SESSION['cart'])) {
    $product_ids = array_column($_SESSION['cart'], 'product_id');
}else{
    $product_ids=array();
}

if (isset($_POST['add'])) {
    if (isset($_SESSION['cart'])) {
        

            $count = count($_SESSION['cart']);
            $product = array(
                'product_id' => $_POST['product_id'],
                'quantity'=> 1
            );
          array_push($_SESSION['cart'],$product);
            array_push($product_ids,$_POST['product_id']);
            
        
    } else {
        $product = array(
            'product_id' => $_POST['product_id'],
            'quantity'=> 1
   
        );
        $_SESSION['cart'][0] = $product;
        array_push($product_ids,$_POST['product_id']);
    }
}

print_r($product_ids);

print_r($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title><?=$lang['title'] ?></title>
</head>

<body>
    <h1><?=$lang['title'] ?></h1>
    <h1>Welcome <?= $user_data['user_name']?></h1>
    <a href="logout.php">Logout</a>
    <div class="container">
        <ul class="proditems">
            <?php foreach ($result as $r): ?>
                <?php if (!in_array($r['product_id'], $product_ids)): ?>
                    <li>
                        <form action="index.php" method="POST">
                            <div class="proditem">
                                <div class="prodimage">
                                    <img src="uploads/<?= $r['product_image'] ?>">
                                </div>
                                <div class="proddetails">
                                    <ul>
                                        <li><?=$lang['product_title'] ?>: <?= $r['title'] ?></li>
                                        <li><?=$lang['product_description'] ?>: <?= $r['description'] ?></li>
                                        <li><?=$lang['product_price'] ?>: <?= $r['price'] ?>$ </li>
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
        <a href="cart.php"><?=$lang['cart'] ?></a>
    </div>
    <script>


    </script>
</body>

</html>