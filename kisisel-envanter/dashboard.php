<?php
session_start();
require 'config.php';

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];

// Yeni eşya eklendiyse
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["isim"])) {
    $isim = trim($_POST["isim"]);
    $kategori = trim($_POST["kategori"]);
    $aciklama = trim($_POST["aciklama"]);

    $stmt = $conn->prepare("INSERT INTO envanter (kullanici_id, isim, kategori, aciklama) VALUES (?, ?, ?, ?)");
    $stmt->execute([$kullanici_id, $isim, $kategori, $aciklama]);
}

// Kullanıcının tüm eşyalarını getir
$stmt = $conn->prepare("SELECT * FROM envanter WHERE kullanici_id = ? ORDER BY id DESC");
$stmt->execute([$kullanici_id]);
$esya_listesi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Envanter Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(244, 226, 240); /* Soft açık mavi-gri arkaplan */
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color:rgb(243, 150, 228);
            color: white;
            font-weight: 500;
            font-size: 1.1rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        h2 {
            color:rgb(20, 39, 211);
        }
        table th {
            background-color:rgb(230, 198, 232);
        }
        .btn-outline-danger, .btn-outline-warning {
            border-width: 1px;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Hoş geldin, <?php echo htmlspecialchars($_SESSION["kullanici_ad"]); ?>!</h2>
        <a href="logout.php" class="btn btn-outline-danger">Çıkış Yap</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">Yeni Eşya Ekle</div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Eşya Adı</label>
                    <input type="text" name="isim" class="form-control" required placeholder="Eşya adını yazın">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="">Kategori seçiniz</option>
                        <option value="Elektronik">Elektronik</option>
                        <option value="Giyim">Giyim</option>
                        <option value="Kitap">Kitap</option>
                        <option value="Ev Eşyası">Ev Eşyası</option>
                        <option value="Kırtasiye">Kırtasiye</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Açıklama</label>
                    <textarea name="aciklama" class="form-control" placeholder="Varsa açıklama ekleyin..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Ekle</button>
            </form>
        </div>
    </div>

    <h4 class="mb-3">Envanter Listesi</h4>
    <?php if (count($esya_listesi) == 0): ?>
        <div class="alert alert-secondary">Henüz hiç eşya eklenmedi.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped bg-white shadow-sm rounded">
                <thead>
                    <tr>
                        <th>Ad</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($esya_listesi as $esya): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($esya["isim"]); ?></td>
                            <td><?php echo htmlspecialchars($esya["kategori"]); ?></td>
                            <td><?php echo htmlspecialchars($esya["aciklama"]); ?></td>
                            <td>
                                <a href="edit_item.php?id=<?php echo $esya["id"]; ?>" class="btn btn-sm btn-outline-warning">Düzenle</a>
                                <a href="delete_item.php?id=<?php echo $esya["id"]; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Silmek istediğinize emin misiniz?')">Sil</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
