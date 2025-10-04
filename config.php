<?php 

// Connection to database

try{
    $dsn = 'mysql:host=localhost;dbname=contact_form_db;charset=utf8mb4;';
    $user = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => false
    ];

    $conn = new PDO($dsn, $user, $password, $options);

}catch(PDOException $e){
    throw new Exception('Database connection error ' . $e->getMessage());
}