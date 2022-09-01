<?php

require 'config.php';

session_start();

$lang = detectLanguage();
include "languages/$lang.php";

function connectDB()
{
    try {
        $server = SERVER_NAME;
        $dbName = DATABASE_NAME;
        $conn = new PDO("mysql:host=$server;dbname=$dbName", USER_NAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        exit($e->getmessage());
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
    global $lang;
    return $lang[$label];
}
