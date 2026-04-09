<?php
session_start();
include 'db.php';

$mesaj = "";

if(isset($_POST['kayit'])) {
    $ad_soyad = $_POST['ad_soyad'];
    $email = $_POST['email'];
    $sifre = $_POST['sifre'];

    // Şifreyi gerçek sistemlerdeki gibi kriptoluyoruz (Hashleme)
    $kriptolu_sifre = password_hash($sifre, PASSWORD_DEFAULT);

    // E-posta daha önce kayıtlı mı diye kontrol et
    $kontrol_sorgu = "SELECT * FROM istifadeciler WHERE email = '$email'";
    $kontrol_sonuc = $baglanti->query($kontrol_sorgu);

    if($kontrol_sonuc->num_rows > 0) {
        $mesaj = "<div class='hata'>Bu e-poçt ünvanı artıq qeydiyyatdan keçib!</div>";
    } else {
        // Yeni kullanıcıyı kaydet
        $kayit_sorgu = "INSERT INTO istifadeciler (ad_soyad, email, sifre) VALUES ('$ad_soyad', '$email', '$kriptolu_sifre')";
        
        if($baglanti->query($kayit_sorgu) === TRUE) {
            $mesaj = "<div class='basarili'>Qeydiyyat uğurla tamamlandı! İndi daxil ola bilərsiniz.</div>";
        } else {
            $mesaj = "<div class='hata'>Xəta baş verdi: " . $baglanti->error . "</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Qeydiyyat</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-image: url('https://www.mortenson.com/adobe/dynamicmedia/deliver/dm-aid--5281e58a-45fd-4a4d-875a-bc4af2ea6396/geodispark-nashvillestadium-hero.jpg?width=1280&quality=82&preferwebp=true'); background-size: cover; background-attachment: fixed; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .kutu { background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); width: 320px; text-align: center; }
        .kutu h2 { margin-top: 0; color: #2c3e50; margin-bottom: 25px; }
        .input-grup { margin-bottom: 15px; text-align: left; }
        .input-grup label { display: block; margin-bottom: 5px; color: #555; font-weight: bold; font-size: 14px;}
        .input-grup input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        .btn { background-color: #27ae60; color: white; border: none; padding: 12px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .btn:hover { background-color: #219653; }
        .hata { color: red; font-size: 14px; margin-bottom: 10px; }
        .basarili { color: green; font-size: 14px; margin-bottom: 10px; font-weight: bold; }
        .link { display: block; margin-top: 15px; color: #3498db; text-decoration: none; font-size: 14px;}
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="kutu">
        <h2>📝 Qeydiyyatdan Keç</h2>
        <?= $mesaj ?>
        <form method="POST" action="">
            <div class="input-grup">
                <label>Ad və Soyad</label>
                <input type="text" name="ad_soyad" required>
            </div>
            <div class="input-grup">
                <label>E-poçt (Email)</label>
                <input type="email" name="email" required>
            </div>
            <div class="input-grup">
                <label>Şifrə</label>
                <input type="password" name="sifre" required>
            </div>
            <input type="submit" name="kayit" value="Qeydiyyatı Tamamla" class="btn">
        </form>
        <a href="login.php" class="link">Artıq hesabınız var? Daxil olun</a>
    </div>
</body>
</html>