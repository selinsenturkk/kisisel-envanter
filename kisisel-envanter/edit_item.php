<?php
session_start();
require 'config.php';

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];
$id = $_GET["id"] ?? null;
$hata = "";

// GÜNCELLEME İŞLEMİ
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["isim"])) {
    $id = $_POST["id"];
    $isim = trim($_POST["isim"]);
    $kategori = trim($_POST["kategori"]);
    $aciklama = trim($_POST["aciklama"]);

    $stmt = $conn->prepare("UPDATE envanter SET isim = ?, kategori = ?, aciklama = ? WHERE id = ? AND kullanici_id = ?");
    $stmt->execute([$isim, $kategori, $aciklama, $id, $kullanici_id]);

    header("Location: dashboard.php");
    exit;
}

// VERİYİ ÇEK
if ($id) {
    $stmt = $conn->prepare("SELECT * FROM envanter WHERE id = ? AND kullanici_id = ?");
    $stmt->execute([$id, $kullanici_id]);
    $esya = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$esya) {
        $hata = "Eşya bulunamadı.";
    }
} else {
    $hata = "Geçersiz ID.";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Eşya Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(244, 226, 240);
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            margin-top: 60px;
            border-radius: 10px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: rgb(243, 150, 228);
            color: white;
            font-weight: 500;
            font-size: 1.2rem;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
        }
        .btn-success {
            background-color: rgb(75, 180, 90);
            border: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Eşyayı Düzenle</div>
        <div class="card-body">
            <?php if ($hata): ?>
                <div class="alert alert-danger"><?php echo $hata; ?></div>
                <a href="dashboard.php" class="btn btn-secondary">Geri Dön</a>
            <?php else: ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo $esya["id"]; ?>">
                    <div class="mb-3">
                        <label class="form-label">Eşya Adı</label>
                        <input type="text" name="isim" class="form-control" value="<?php echo htmlspecialchars($esya["isim"]); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori" class="form-select" required>
                            <option value="">Kategori seçiniz</option>
                            <option value="Elektronik" <?php if($esya["kategori"]=="Elektronik") echo "selected"; ?>>Elektronik</option>
                            <option value="Giyim" <?php if($esya["kategori"]=="Giyim") echo "selected"; ?>>Giyim</option>
                            <option value="Kitap" <?php if($esya["kategori"]=="Kitap") echo "selected"; ?>>Kitap</option>
                            <option value="Ev Eşyası" <?php if($esya["kategori"]=="Ev Eşyası") echo "selected"; ?>>Ev Eşyası</option>
                            <option value="Kırtasiye" <?php if($esya["kategori"]=="Kırtasiye") echo "selected"; ?>>Kırtasiye</option>
                            <option value="Diğer" <?php if($esya["kategori"]=="Diğer") echo "selected"; ?>>Diğer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" class="form-control"><?php echo htmlspecialchars($esya["aciklama"]); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Kaydet</button>
                    <a href="dashboard.php" class="btn btn-secondary">İptal</a>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
