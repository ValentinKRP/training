<?php

function connectDB()
{
    require_once 'config.php';
    try{
        $server=SERVER_NAME;
        $conn=new PDO("mysql:host=$server;dbname=training", USER_NAME, PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
        
      
    }catch(PDOException $e){
    echo ' Connection failed: ' . $e->getMessage();
    }
    return $conn;
}