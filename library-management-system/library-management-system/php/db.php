<?php
$host = 'localhost';
$db_name = 'library';
$username = 'root'; // Replace with your database username
$password = ''; // Replace with your database password

try {
    $con = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
