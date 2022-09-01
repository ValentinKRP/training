<?php

include 'common.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($userName) && !empty($password) && !is_numeric($userName)) {
        $conn = connectDB();
        $sql = 'SELECT * FROM users where user_name=? limit 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userName]);
        $count = $stmt->rowCount();
        if ($stmt && $count > 0) {
            $userData = $stmt->fetch();
            if ($userData['password'] == $password) {
                $_SESSION['user_role'] = $userData['admin'];

                header('Location: products.php');
                die;
            }
        } else {
            $errorLogin = 'This account doesnt exists';
        }
    } else {
        $errorLogin = 'Please enter valid data';
    }
}

require 'header.php';

?>
<body>
    <div id="box">
        <form method="POST">
            <div><?= translate('login')?></div>
            <input type="text" name="user_name"><br><br>
            <input type="password" name="password"><br><br>

            <input type="submit" value="Login">
            <?php if (isset($errorLogin)) : ?>
                <span  class="error"><?= translate('login_error') ?></span>
                <br>
            <?php endif; ?>
            <a href="signup.php"><?= translate('signup') ?></a>

        </form>

    </div>
<?php require 'footer.php' ?>
