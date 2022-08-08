<?php

session_start();


include 'common.php';
$lang=translate();
include "languages/$lang.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $conn = connectDB();
        $sql = 'SELECT * from users where user_name=? limit 1';
        $stmt = $conn->prepare($sql);

        $stmt->execute([$user_name]);
        $count = $stmt->rowCount();
        if ($stmt && $count > 0) {
            $user_data = $stmt->fetch();

            if ($user_data['password'] == $password) {

                $_SESSION['user_id'] = $user_data['user_id'];
                header("Location: index.php");
                die;
            }
        }else{
            $errorLogin='This account doesnt exists';
           
        }
        
    } else {
        $errorLogin='Please enter valid data';
           
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
    <title>Login</title>
</head>

<body>
    <div id="box">
        <form method="POST">
            <div><?= $lang['login'] ?></div>
            <input type="text" name="user_name"><br><br>
            <input type="password" name="password"><br><br>

            <input type="submit" value="Login">
            <?php if(isset($errorLogin)): ?>
                <span  class="error"><?= $errorLogin ?></span>
                <br>
            <?php endif; ?>
            <a href="signup.php"><?= $lang['signup'] ?></a>

        </form>

    </div>
</body>
</html>