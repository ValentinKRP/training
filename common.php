<?php

require 'config.php';
function connectDB()
{
    try {
        $server = SERVER_NAME;
        $conn = new PDO("mysql:host=$server;dbname=training", USER_NAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo ' Connection failed: ' . $e->getMessage();
    }
    return $conn;
}

function randomNum($length)
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

function sendEmail($user_name, $details, $comments, $order_date)
{
    $email_to = SHOP_MANAGER;
    $subject = 'New order placed';

    $headers = "From: demo mail <valentin.carp15@gmail.com>\r\n";
    $headers .= "MIME-Version:1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


    $swap_var = array(
        "{ORDER_DETAILS}" => $details,
        "{ORDER_COMMENTS}" => $comments,
        "{USER_NAME}" => $user_name,
        "{ORDER_DATE}" => $order_date


    );

    ob_start();
    include 'template.php';
    $message = ob_get_clean();

    foreach (array_keys($swap_var) as $key) {
        if (strlen($key) > 2 && trim($key) != "") {
            $message = str_replace($key, $swap_var[$key], $message);
        }
    }


    mail($email_to, $subject, $message, $headers);
}
function translate()
{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    return $lang;
}

function testInput($data)
{
$data=strip_tags($data);
$data=htmlspecialchars($data);
return $data;
}
