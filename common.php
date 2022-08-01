<?php

function connectDB()
{
    require_once 'config.php';
    try {
        $server = SERVER_NAME;
        $conn = new PDO("mysql:host=$server;dbname=training", USER_NAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo ' Connection failed: ' . $e->getMessage();
    }
    return $conn;
}

function check_login($con)
{

    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
        $stmt = $con->prepare('SELECT * from users where user_id=? LIMIT 1');
        $stmt->execute([$id]);
        if ($stmt) {
            $user_data = $stmt->fetch();
            return $user_data;
        }
    }

    header("Location: login.php");
    die;
}

function random_num($length)
{
    $text = '';
    if ($length < 5) {
        $length = 5;
    }
    $len = rand(4, $length);
    for ($i = 0; $i < $len; $i++) {
        $text .= rand(0, 9);
    }
    return $text;
}

function send_email($user_name,$details,$comments)
{

    $template_file = "./template.php";

    $email_to ="valentincarp2000@gmail.com";
    $subject = 'New order placed';

    $headers = "From: demo mail <valentin.carp15@gmail.com>\r\n";
    $headers .= "MIME-Version:1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
    $sender="valentin.carp15@gmail.com";

    $swap_var = array(
        "{ORDER_DETAILS}" => $details,
        "{ORDER_COMMENTS}" => $comments,
        "{USER_NAME}" => $user_name

    );
    $message2="test";

    if (file_exists($template_file)) {
        $message = file_get_contents($template_file);
    } else {
        die("unable to find file");
    }

    foreach (array_keys($swap_var) as $key) {
        if (strlen($key) > 2 && trim($key) != "") {
            $message = str_replace($key, $swap_var[$key], $message);
        }
    }

 
    if (mail($email_to, $subject, $message2, $headers)){
        echo " mail succes";
    }else{

    echo " Error sending mail";
    }
}
