<?php

include 'common.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    exit('You are not administrator');
}

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
    $sql = 'SELECT * FROM `products` WHERE id=? limit 1';
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($_POST['edit_product'])) {
        if (empty($_POST['product_name'])) {
            $productNameError = 'ok';
        }
        if (empty($_POST['product_desc'])) {
            $productDescError = 'ok';
        }
        if (empty($_POST['product_price'])) {
            $productPriceError = 'ok';
        }
        if (!isset($productNameError) && !isset($productDescError) && !isset($productPriceError)) {
            $productTitle = testInput($_POST['product_name']);
            $productDesc = testInput($_POST['product_desc']);
            $productPrice = testInput($_POST['product_price']);

            if ($_FILES['product_image']['size'] !== 0) {
                $productImage = $_FILES['product_image'];
                $imageName = $_FILES['product_image']['name'];
                $imageType = $_FILES['product_image']['type'];
                $imageError = $_FILES['product_image']['error'];
                $imageExtension = explode('.', $imageName);
                $imageActualExtension = strtolower(end($imageExtension));

                $allowed = ['jpg', 'jpeg', 'png'];

                if (in_array($imageActualExtension, $allowed)) {
                    if ($imageError === 0) {
                        $imageNameNew = uniqid('', true) . "." . "$imageActualExtension";
                        $fileDestionation = 'uploads/' . $imageNameNew;
                        $sql = 'UPDATE  products  SET title=?, description=?, price=?, product_image=? WHERE id=?';
                        $stmt = $conn->prepare($sql);

                        if (move_uploaded_file($_FILES['product_image']['tmp_name'], $fileDestionation)) {
                            $stmt->execute([$productTitle, $productDesc, $productPrice, $imageNameNew, $id]);
                            unset($_SESSION['product_name']);
                            unset($_SESSION['product_desc']);
                            unset($_SESSION['product_price']);
                            header('Location: products.php');
                            die;
                        };
                    }
                } else {
                    $errorImageType = 'ok';
                }
            } else {
                unset($_SESSION['product_name']);
                unset($_SESSION['product_desc']);
                unset($_SESSION['product_price']);
                $sql = 'UPDATE  products  SET title=?, description=?, price=? WHERE id=?';
                $stmt = $conn->prepare($sql);
                $stmt->execute([$productTitle, $productDesc, $productPrice, $id]);
                header('Location: products.php');
                die;
            }
        }
    }
}

if (isset($_POST['add_product'])) {
    if (empty($_POST['product_name'])) {
        $productNameError = 'ok';
    }
    if (empty($_POST['product_desc'])) {
        $productDescError = 'ok';
    }
    if (empty($_POST['product_price'])) {
        $productPriceError = 'ok';
    }
    if ($_FILES['product_image']['size'] == 0) {
        $errorImage = 'ok';
    }
    if (!isset($productNameError) && !isset($productDescError) && !isset($productPriceError) && !isset($errorImage)) {
        $productTitle = testInput($_POST['product_name']);
        $productDesc = testInput($_POST['product_desc']);
        $productPrice = testInput($_POST['product_price']);

        if (isset($_FILES['product_image']) && $_FILES['product_image']['size'] !== 0) {
            $productImage = $_FILES['product_image'];
            $imageName = $_FILES['product_image']['name'];
            $imageType = $_FILES['product_image']['type'];
            $imageError = $_FILES['product_image']['error'];
            $imageExtension = explode('.', $imageName);
            $imageActualExtension = strtolower(end($imageExtension));

            $allowed = array('jpg', 'jpeg', 'png');

            if (in_array($imageActualExtension, $allowed)) {
                if ($imageError === 0) {
                    $imageNameNew = uniqid('', true) . "." . "$imageActualExtension";
                    $fileDestionation = 'uploads/' . $imageNameNew;

                    $sql = 'INSERT INTO products (title, description, price, product_image) VALUES(?,?,?,?)';
                    $stmt = $conn->prepare($sql);

                    if (move_uploaded_file($_FILES['product_image']['tmp_name'], $fileDestionation)) {
                        $stmt->execute([$productTitle, $productDesc, $productPrice, $imageNameNew]);
                        unset($_SESSION['product_name']);
                        unset($_SESSION['product_desc']);
                        unset($_SESSION['product_price']);
                        header('Location: products.php');
                        die;
                    };
                } else {
                    $errorImage = 'Ok';
                }
            } else {
                $errorImageType = 'Ok';
            }
        } else {
            $errorImage = 'Ok';
        }
    }
}

require 'header.php';

?>

    <div class="container">
        <h2> <?= isset($_GET['id']) ? translate('edit') : translate('add') ?></h2>

        <hr>
    
            <form action="product.php<?= isset($_GET['id']) ? '?id=' . $_GET['id'] . '' : '' ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label><?= translate('product_name') ?> </label>
                    <input type="text" name="product_name" 
                     <?php if (isset($_SESSION['product_name'])) :?> 
                            value="<?= $_SESSION['product_name'] ?>"
                     <?php elseif (isset($_GET['id'])) : ?> 
                             value="<?= $product['title'] ?>" 
                     <?php endif; ?>>
                    <?php if (isset($productNameError)) : ?>
                            <br>
                            <span class="errors"><?=  translate('product_name_error') ?></span>
                            <br>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label><?= translate('product_description') ?>: </label>
                    <input type="text" name="product_desc"
                     <?php if (isset($_SESSION['product_desc'])) :?> 
                            value="<?= $_SESSION['product_desc'] ?>"
                     <?php elseif (isset($_GET['id'])) : ?> 
                            value="<?= $product['description'] ?>" 
                     <?php endif; ?>>
                    <?php if (isset($productDescError)) : ?>
                            <br>
                            <span class="errors"><?=  translate('product_details_error') ?></span>
                            <br>
                    <?php endif; ?>
                    
                </div>
                <div class="form-group">
                    <label><?= translate('product_price') ?>: </label>
                    <input type="number" name="product_price" 
                    value=
                     <?php if (isset($_SESSION['product_price'])) :?> 
                            <?= $_SESSION['product_price'] ?>
                     <?php elseif (isset($_GET['id'])) : ?> 
                             <?= $product['price'] ?> 
                     <?php endif; ?>>
                     <?php if (isset($productPriceError)) : ?>
                            <br>
                            <span class="errors"><?=  translate('product_price_error') ?></span>
                            <br>
                     <?php endif; ?>
                </div>
                <div class="form-group">
                    <label><?= translate('product_image') ?>: </label>
                    <input type="file" name="product_image">
                    <?php if (isset($_GET['id'])) :?>
                        <img src="uploads/<?= $product['product_image'] ?>"> 
                    <?php endif; ?>
                    <?php if (isset($errorImage)) : ?>
                        <br>
                        <span style="color:red;"><?= translate('image_error') ?></span>
                        <br>
                    <?php endif; ?>
                    <?php if (isset($errorImageType)) : ?>
                        <br>
                        <span style="color:red;"><?= translate('image_error_type') ?></span>
                        <br>
                    <?php endif; ?>
                </div>

                <?php if (isset($_GET['id'])) : ?>
                    <button type="submit" name="edit_product"><?= translate('edit_product') ?></button>
                <?php else : ?>
                    <button type="submit" name="add_product"><?= translate('add_product') ?></button>
                <?php endif; ?>
                <a href="products.php"><?= translate('navigate_products') ?></a>
                </form>

    </div>
<?php require 'footer.php'; ?>