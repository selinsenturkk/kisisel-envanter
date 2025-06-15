<?php
$host = "localhost";
$dbname = "envanter_db";
$username = "root";
$password = "";

// PDO ile bağlantı kuruyoruz
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // PDO hata modunu ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
