<?php
session_start();
require 'config.php';

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $kullanici_id = $_SESSION["kullanici_id"];

    // Silmeden önce bu eşya gerçekten bu kullanıcıya mı ait kontrolü
    $stmt = $conn->prepare("DELETE FROM envanter WHERE id = ? AND kullanici_id = ?");
    $stmt->execute([$id, $kullanici_id]);
}

header("Location: dashboard.php");
exit;
