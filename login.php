<?php
session_start();
include 'db.php';

$hata_mesaji = "";

if(isset($_POST['giris'])) {
    $email_veya_kullanici = $_POST['email'];
    $sifre = $_POST['sifre'];

    // ÖNCE ADMİN Mİ DİYE KONTROL EDELİM (SQLi Zafiyeti Burada)
    $admin_sorgu = "SELECT * FROM admin WHERE kullanici_adi = '$email_veya_kullanici' AND sifre = '$sifre'";
    $admin_sonuc = $baglanti->query($admin_sorgu);

    if($admin_sonuc && $admin_sonuc->num_rows > 0) {
        $_SESSION['giris_yapildi'] = true;
        $_SESSION['rol'] = 'admin';
        header("Location: admin.php");
        exit;
    }

    // EĞER ADMİN DEĞİLSE, NORMAL MÜŞTERİ (İSTİFADƏÇİ) Mİ DİYE KONTROL EDELİM
    $istifadeci_sorgu = "SELECT * FROM istifadeciler WHERE email = '$email_veya_kullanici'";
    $istifadeci_sonuc = $baglanti->query($istifadeci_sorgu);

    if($istifadeci_sonuc && $istifadeci_sonuc->num_rows > 0) {
        $istifadeci = $istifadeci_sonuc->fetch_assoc();
        
        // Veritabanındaki kriptolu şifre ile girilen şifreyi karşılaştırıyoruz
        if(password_verify($sifre, $istifadeci['sifre'])) {
            $_SESSION['giris_yapildi'] = true;
            $_SESSION['rol'] = 'musteri';
            $_SESSION['ad_soyad'] = $istifadeci['ad_soyad'];
            header("Location: index.php"); // Giriş yapınca ana sayfaya yönlendir
            exit;
        } else {
            $hata_mesaji = "Xətalı giriş! Şifrə yanlışdır.";
        }
    } else {
        $hata_mesaji = "Xətalı giriş! İstifadəçi tapılmadı.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background-image: url('https://www.mortenson.com/adobe/dynamicmedia/deliver/dm-aid--5281e58a-45fd-4a4d-875a-bc4af2ea6396/geodispark-nashvillestadium-hero.jpg?width=1280&quality=82&preferwebp=true'); background-size: cover; background-attachment: fixed; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-kutu { background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 12px; box-shadow: 0 15px 35px rgba(0,0,0,0.5); width: 320px; text-align: center; }
        .login-kutu h2 { margin-top: 0; color: #2c3e50; margin-bottom: 25px; }
        .input-grup { margin-bottom: 20px; text-align: left; }
        .input-grup label { display: block; margin-bottom: 8px; color: #555; font-weight: bold; font-size: 14px;}
        .input-grup input { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        .giris-btn { background-color: #2c3e50; color: white; border: none; padding: 14px; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; margin-top: 10px; }
        .giris-btn:hover { background-color: #1a252f; }
        .hata { color: #e74c3c; background-color: #fadbd8; padding: 10px; border-radius: 5px; font-size: 13px; margin-bottom: 20px; font-weight: bold; }
        .link { display: inline-block; margin-top: 20px; color: #3498db; text-decoration: none; font-size: 14px; margin-right: 15px;}
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="login-kutu">
        <h2>🔑 Sistemə Daxil Ol</h2>
        
        <?php if($hata_mesaji != "") { echo "<div class='hata'>$hata_mesaji</div>"; } ?>

        <form method="POST" action="">
            <div class="input-grup">
                <label>E-poçt (və ya Admin Adı)</label>
                <input type="text" name="email" placeholder="E-poçt ünvanınızı yazın" autocomplete="off" required>
            </div>
            
            <div class="input-grup">
                <label>Şifrə</label>
                <div style="position: relative;">
                    <input type="password" name="sifre" id="sifreKutusu" placeholder="Şifrənizi yazın" required style="padding-right: 40px;">
                    <span id="gozIkoni" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 18px; user-select: none;" title="Şifrəni Göstər">👁️</span>
                </div>
            </div>
            
            <input type="submit" name="giris" value="Daxil Ol" class="giris-btn">
        </form>
        
        <div>
            <a href="index.php" class="link" style="color: #7f8c8d;">⬅ Ana Səhifə</a>
            <a href="register.php" class="link">Qeydiyyatdan Keç</a>
        </div>
    </div>

    <script>
        const sifreKutusu = document.getElementById('sifreKutusu');
        const gozIkoni = document.getElementById('gozIkoni');

        if (gozIkoni) {
            gozIkoni.addEventListener('click', function() {
                if (sifreKutusu.type === 'password') {
                    sifreKutusu.type = 'text';
                    gozIkoni.innerText = '🙈'; // Gizleme ikonu
                    gozIkoni.title = "Şifrəni Gizlət";
                } else {
                    sifreKutusu.type = 'password';
                    gozIkoni.innerText = '👁️'; // Açık göz ikonu
                    gozIkoni.title = "Şifrəni Göstər";
                }
            });
        }
    </script>
</body>
</html>