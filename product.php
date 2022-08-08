<?php

include 'common.php';

session_start();

$lang = translate();
include "languages/$lang.php";


$conn = connectDB();

if (isset($_POST['product_name'])) {
    $_SESSION['product_name'] = $_POST['product_name'];
}
if (isset($_POST['product_desc'])) {
    $_SESSION['product_desc'] = $_POST['product_desc'];
}
if (isset($_POST['product_price'])) {
    $_SESSION['product_price'] = $_POST['product_price'];
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * from `products` WHERE product_id=? limit 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);



    if (isset($_POST['edit_product'])) {
        $product_title = testInput($_POST['product_name']);
        $product_desc = testInput($_POST['product_desc']);
        $product_price = testInput($_POST['product_price']);


        if ($_FILES['product_image']['size'] !== 0) {

            $product_image = $_FILES['product_image'];
            $image_name = $_FILES['product_image']['name'];
            $image_type = $_FILES['product_image']['type'];
            $image_error = $_FILES['product_image']['error'];
            $image_extention = explode('.', $image_name);
            $image_actual_extension = strtolower(end($image_extention));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($image_actual_extension, $allowed)) {
                if ($image_error === 0) {
                    $image_name_new = uniqid('', true) . "." . "$image_actual_extension";
                    $file_destination = 'uploads/' . $image_name_new;

                    $sql = "UPDATE  products  SET title=?, description=?, price=?, product_image=? WHERE product_id=?";

                    $stmt = $conn->prepare($sql);


                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_destination)) {

                        $stmt->execute([$product_title, $product_desc, $product_price, $image_name_new, $id]);
                        unset($_SESSION['product_name']);
                        unset($_SESSION['product_desc']);
                        unset($_SESSION['product_price']);
                        header("Location: products.php");
                        die;
                      
                    };
                } else {
                }
            } else {
                $errorImage = 'File is not an image';
            }
        } else {
            unset($_SESSION['product_name']);
            unset($_SESSION['product_desc']);
            unset($_SESSION['product_price']);
            $sql = "UPDATE  products  SET title=?, description=?, price=? WHERE product_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$product_title, $product_desc, $product_price, $id]);
            header("Location: products.php");
            die;
          
        }
    }
}

if (isset($_POST['add_product'])) {
    $product_title = testInput($_POST['product_name']);
    $product_desc = testInput($_POST['product_desc']);
    $product_price = testInput($_POST['product_price']);


    if (isset($_FILES['product_image']) && $_FILES['product_image']['size'] !== 0) {

        $product_image = $_FILES['product_image'];
        $image_name = $_FILES['product_image']['name'];
        $image_type = $_FILES['product_image']['type'];
        $image_error = $_FILES['product_image']['error'];
        $image_extention = explode('.', $image_name);
        $image_actual_extension = strtolower(end($image_extention));

        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($image_actual_extension, $allowed)) {
            if ($image_error === 0) {
                $image_name_new = uniqid('', true) . "." . "$image_actual_extension";
                $file_destination = 'uploads/' . $image_name_new;

                $sql = "INSERT INTO products (title, description, price, product_image) VALUES(?,?,?,?)";
                $stmt = $conn->prepare($sql);


                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $file_destination)) {

                    $stmt->execute([$product_title, $product_desc, $product_price, $image_name_new]);
                    unset($_SESSION['product_name']);
                    unset($_SESSION['product_desc']);
                    unset($_SESSION['product_price']);
                    header("Location: products.php");
                    die;
                };
            } else {
                $errorImage = 'Somethig went wrong when uploading the file';
            }
        } else {
            $errorImage = 'File is not an image';
        }
    } else {
        $errorImage = 'Please insert an image';
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet">
    <title>Product</title>
</head>

<body>
    <div class="container">
        <h2> <?= isset($_POST['edit']) ? "Edit product" : "Add product" ?></h2>

        <hr>
           <?php if(isset($_GET['id'])): ?>
            <form action="product.php?id=<?= $_GET['id']  ?>"  method="POST" enctype="multipart/form-data">
           <?php else: ?>
            <form action="product.php"  method="POST" enctype="multipart/form-data">
           
<?php endif; ?>
                <div class="form-group">
                    <label for=""><?= $lang['product_name'] ?> </label>
                    <input type="text" name="product_name" placeholder="product name"  <?php if (isset($_GET['id'])) : ?> value="<?= $result['title'] ?>" <?php endif; ?> <?php if (isset($_SESSION['product_name'])) : ?> value="<?= $_SESSION['product_name'] ?>" <?php endif; ?> required>

                </div>
                <div class="form-group">
                    <label for=""><?= $lang['product_description'] ?>: </label>
                    <input type="text" name="product_desc" placeholder="" <?php if (isset($_GET['id'])) : ?> value="<?= $result['description'] ?>" <?php endif; ?> <?php if (isset($_SESSION['product_desc'])) : ?> value="<?= $_SESSION['product_desc'] ?>" <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for=""><?= $lang['product_price'] ?>: </label>
                    <input type="number" name="product_price" placeholder="" <?php if (isset($_GET['id'])) : ?> value="<?= $result['price'] ?>" <?php endif; ?> <?php if (isset($_SESSION['product_price'])) : ?> value="<?= $_SESSION['product_price'] ?>" <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label><?= $lang['product_image'] ?>: </label>
                    <input type="file" name="product_image">
                    <?php if (isset($errorImage)) : ?>
                        <span style="color:red;"><?= $errorImage ?></span>
                        <br>
                    <?php endif; ?>
                </div>

                <?php if (isset($_GET['id'])) : ?>
                    <button type="submit" name="edit_product"><?= $lang['edit_product'] ?></button>
                <?php else : ?>
                    <button type="submit" name="add_product"><?= $lang['add_product'] ?></button>
                <?php endif; ?>
                <a href="products.php"><?= $lang['navigate_products'] ?></a>
                </form>

    </div>
</body>

</html>