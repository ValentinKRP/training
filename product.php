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

if(isset($_POST['product_name'])){
    $_SESSION['product_name']=$_POST['product_name'];
}
if(isset($_POST['product_desc'])){
    $_SESSION['product_desc']=$_POST['product_desc'];
}
if(isset($_POST['product_price'])){
    $_SESSION['product_price']=$_POST['product_price'];
}


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql="SELECT * from `products` WHERE product_id=? limit 1";
    $stmt=$conn->prepare($sql);
    $stmt->execute([$id]);
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
 


    if(isset($_POST['edit_product'])){
        $product_title = strip_tags($_POST['product_name']);
        $product_desc = strip_tags($_POST['product_desc']);
        $product_price = strip_tags($_POST['product_price']);
       
        
        if ($_FILES['product_image']['size']!==0){
    
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
    
                        $stmt->execute([$product_title, $product_desc, $product_price, $image_name_new,$id]);
                        unset($_SESSION['product_name']);
                        unset($_SESSION['product_desc']);
                        unset($_SESSION['product_price']);
                        echo '<script>alert("Product was updated")</script>';
                    };
                } else {
                    echo "<script>alert('Error when uploading file!')
                </script>";
                }
            } else {
                
                    echo '<script>alert("File uploaded is not an image")</script>';
            }
        }else{
            unset($_SESSION['product_name']);
            unset($_SESSION['product_desc']);
            unset($_SESSION['product_price']);
            $sql = "UPDATE  products  SET title=?, description=?, price=? WHERE product_id=?";
    
            $stmt = $conn->prepare($sql);
                $stmt->execute([$product_title, $product_desc, $product_price,$id]);
                echo '<script>alert("Product was updated")</script>';
        } 
    }

    

}




if (isset($_POST['add_product'])) {
    $product_title = strip_tags($_POST['product_name']);
    $product_desc = strip_tags($_POST['product_desc']);
    $product_price = strip_tags($_POST['product_price']);
   

    if (isset($_FILES['product_image'])) {

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
                    echo '<script>alert("Product was added")</script>';
                };
            } else {
                echo "<script>alert('Error when uploading file!')
            </script>";
            }
        } else {
            echo "<script>alert('The file inserted is not an image!')
        window.location.href='product.php'</script>";
        }
    } else {
        echo "<script>alert('Insert an image')
        window.location.href='product.php'</script>";
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
    <!-- <?= isset($_POST['edit']) ? "<h2> Edit product </h2>" : "<h2> Add product </h2>" ?> -->
        <?php if (isset($_GET['id'])) : ?>
            <h2> Edit product </h2>
        <?php else : ?>
            <h2> Add product </h2>
        <?php endif; ?>
        <hr>
        <?php if (isset($_GET['id'])) : ?>
            <form action="product.php?id=<?=$_GET['id'] ?>" method="POST" enctype="multipart/form-data">
        <?php else : ?>
            <form action="product.php" method="POST" enctype="multipart/form-data">
        <?php endif; ?>
       
            <div class="form-group">
                <label for="">Product Name: </label>
                <input type="text" name="product_name" placeholder="product name" 
                <?php if(isset($_GET['id'])): ?> 
                    value="<?=$result['title'] ?>" <?php endif; ?> 
                    <?php if(isset($_SESSION['product_name'])): ?> 
                    value="<?=$_SESSION['product_name'] ?>" <?php endif; ?>    
                    required>
                  
            </div>
            <div class="form-group">
                <label for="">Description: </label>
                <input type="text" name="product_desc" placeholder="" 
                <?php if(isset($_GET['id'])): ?> 
                    value="<?=$result['description'] ?>" <?php endif; ?> 
                    <?php if(isset($_SESSION['product_desc'])): ?> 
                    value="<?=$_SESSION['product_desc'] ?>" <?php endif; ?> 
                required>
            </div>
            <div class="form-group">
                <label for="">Price: </label>
                <input type="number" name="product_price" placeholder=""
                <?php if(isset($_GET['id'])): ?> 
                    value="<?=$result['price'] ?>" <?php endif; ?> 
                    <?php if(isset($_SESSION['product_price'])): ?> 
                    value="<?=$_SESSION['product_price'] ?>" <?php endif; ?>    
                required>
            </div>
            <div class="form-group">
                <label>Product image: </label>
                <input type="file" name="product_image">
            </div>

            <?php if (isset($_GET['id'])) : ?>
                <button type="submit" name="edit_product">Edit product</button>
            <?php else : ?>
                <button type="submit" name="add_product">Add product</button>
            <?php endif; ?>
            <a href="products.php">Back to products</a>
        </form>

    </div>
</body>

</html>