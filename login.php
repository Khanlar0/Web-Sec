<?php
session_start();
include 'db.php';

$hata_mesaji = "";

if(isset($_POST['giris'])) {
    $kullanici = $_POST['kullanici_adi'];
    $sifre = $_POST['sifre'];

    $sorgu = "SELECT * FROM admin WHERE kullanici_adi = '$kullanici' AND sifre = '$sifre'";
    $sonuc = $baglanti->query($sorgu);


    if($sonuc && $sonuc->num_rows > 0) {
        $_SESSION['giris_yapildi'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $hata_mesaji = "Xətalı giriş! İstifadəçi adı və ya şifrə yanlışdır.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Girişi</title>
    <style>
        /* Arka plan ve genel ayarlar */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://www.mortenson.com/adobe/dynamicmedia/deliver/dm-aid--5281e58a-45fd-4a4d-875a-bc4af2ea6396/geodispark-nashvillestadium-hero.jpg?width=1280&quality=82&preferwebp=true');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        
        /* Formu tutan ortadaki beyaz kart */
        .login-kutu {
            background: rgba(255, 255, 255, 0.95); /* Hafif saydam beyaz */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5); /* Derinlik veren gölge */
            width: 320px;
            text-align: center;
        }

        .login-kutu h2 {
            margin-top: 0;
            color: #2c3e50;
            margin-bottom: 25px;
            font-size: 24px;
        }

        /* Giriş kutucukları ayarları */
        .input-grup {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-grup label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-size: 14px;
            font-weight: bold;
        }

        .input-grup input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 15px;
            transition: border-color 0.3s;
        }

        /* Kutuya tıklanınca etrafında oluşan mavi çerçeve */
        .input-grup input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        /* Giriş Butonu */
        .giris-btn {
            background-color: #2c3e50;
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .giris-btn:hover {
            background-color: #1a252f;
        }

        /* Hata mesajı tasarımı */
        .hata {
            color: #e74c3c;
            background-color: #fadbd8;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        /* Ana sayfaya dön linki */
        .geri-don {
            display: inline-block;
            margin-top: 20px;
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .geri-don:hover {
            color: #3498db;
        }
    </style>
</head>
<body>

    <div class="login-kutu">
        <h2>Admin Paneli</h2>
        
        <?php 
        // Eğer hata varsa ekranda göster
        if($hata_mesaji != "") { 
            echo "<div class='hata'>$hata_mesaji</div>"; 
        } 
        ?>

        <form method="POST" action="">
            <div class="input-grup">
                <label>İstifadəçi Adı</label>
                <input type="text" name="kullanici_adi" placeholder="İstifadəçi adınızı yazın" autocomplete="off">
            </div>
            
            <div class="input-grup">
                <label>Şifrə</label>
                <input type="password" name="sifre" placeholder="Şifrənizi yazın">
            </div>
            
            <input type="submit" name="giris" value="Sistemə Daxil Ol" class="giris-btn">
        </form>
        
        <a href="index.php" class="geri-don">⬅ Ana Səhifəyə Qayıt</a>
    </div>

</body>
</html>