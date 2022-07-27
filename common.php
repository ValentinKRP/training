<?php

function connectDB()
{
    require_once 'config.php';
    try{
      
        $conn=new PDO("mysql:host=$servername;dbname=training", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       
        
      
    }catch(PDOException $e){
    echo ' Connection failed: ' . $e->getMessage();
    }
    return $conn;
}