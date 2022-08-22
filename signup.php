<?php

include 'common.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        $conn = connectDB();
        $sql = 'INSERT INTO users (user_id,user_name,password) VALUES (?,?,?)';
        $stmt = $conn->prepare($sql);
        $user_id = randomNum(20);
        $stmt->execute([$user_id, $user_name, $password]);

        header("Location: login.php");
        die;
    } else {
        echo "<script>alert('Please enter valid data')
        window.location.href='signup.php'</script>";
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
    <title>SignUp</title>
</head>

<body>
    <div id="box">
        <form method="POST">
            <div>SignUp</div>
            <input type="text" name="user_name"><br><br>
            <input type="password" name="password"><br><br>

            <input type="submit" value="SignUp">

            <a href="login.php">Login </a>

        </form>

    </div>
</body>

</html>
