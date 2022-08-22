<?php

session_start();

require 'config.php';
function connectDB()
{
    try {
        $server = SERVER_NAME;
        $dbName = DATABASE_NAME;
        $conn = new PDO("mysql:host=$server;dbname=$dbName", USER_NAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
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

function sendEmail($userName, $details, $comments, $orderDate)
{
    $emailTo = SHOP_MANAGER;
    $subject = 'New order placed';

    $headers = "From: demo mail <valentin.carp15@gmail.com>\r\n";
    $headers .= "MIME-Version:1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";


    $swapVar = [
        "{ORDER_DETAILS}" => $details,
        "{ORDER_COMMENTS}" => $comments,
        "{USER_NAME}" => $userName,
        "{ORDER_DATE}" => $orderDate
    ];

    ob_start();
    include 'template.php';
    $message = ob_get_clean();

    foreach (array_keys($swapVar) as $key) {
        if (strlen($key) > 2 && trim($key) != "") {
            $message = str_replace($key, $swapVar[$key], $message);
        }
    }


    mail($emailTo, $subject, $message, $headers);
}
function detectLanguage()
{
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    return $lang;
}

function testInput($data)
{

    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}

function translate($label)
{
    $lang = detectLanguage();
    include "languages/$lang.php";

    return $lang[$label];
}
