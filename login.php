<?php
session_start();
require 'config.php';

$hata = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $sifre = $_POST["sifre"];

    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = ?");
    $stmt->execute([$email]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kullanici && password_verify($sifre, $kullanici['parola'])) {
        $_SESSION["kullanici_id"] = $kullanici["id"];
        $_SESSION["kullanici_ad"] = $kullanici["kullanici_adi"];
        header("Location: dashboard.php");
        exit;
    } else {
        $hata = "E-posta veya şifre hatalı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
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
        <div class="card-header">Giriş Yap</div>
        <div class="card-body">
            <?php if ($hata): ?>
                <div class="alert alert-danger"><?php echo $hata; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">E-posta</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Şifre</label>
                    <input type="password" name="sifre" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
                <div class="text-center mt-3">
                    <a href="register.php" class="btn btn-link">Hesabınız yok mu?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
