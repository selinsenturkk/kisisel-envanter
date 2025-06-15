<?php
session_start();
require 'config.php';

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kullanici_adi = trim($_POST["ad"]);
    $email = trim($_POST["email"]);
    $parola = $_POST["sifre"];
    $sifre_tekrar = $_POST["sifre_tekrar"];

    if ($parola !== $sifre_tekrar) {
        $hata = "Şifreler uyuşmuyor!";
    } else {
        $sifre_hash = password_hash($parola, PASSWORD_DEFAULT);

        try {
            $stmt = $conn->prepare("INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES (?, ?, ?)");
            $stmt->execute([$kullanici_adi, $email, $sifre_hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $hata = "Bu e-posta adresi zaten kayıtlı.";
            } else {
                $hata = "Kayıt sırasında bir hata oluştu.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(244, 226, 240);
            font-family: "Segoe UI", sans-serif;
        }
        .card {
            max-width: 500px;
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
        .btn-primary {
            background-color: rgb(100, 30, 200);
            border: none;
        }
        .btn-link {
            font-size: 0.95rem;
            color: rgb(100, 30, 200);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">Kayıt Ol</div>
        <div class="card-body">
            <?php if ($hata): ?>
                <div class="alert alert-danger"><?php echo $hata; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Ad Soyad</label>
                    <input type="text" name="ad" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Şifre</label>
                    <input type="password" name="sifre" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Şifre (Tekrar)</label>
                    <input type="password" name="sifre_tekrar" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Kayıt Ol</button>
                <div class="text-center mt-3">
                    <a href="login.php" class="btn btn-link">Zaten hesabınız var mı?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
