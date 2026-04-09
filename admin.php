<?php
session_start();
include 'db.php'; 

if(!isset($_SESSION['giris_yapildi'])) {
    die("Bu səhifəni görmək üçün yetkiniz yoxdur!");
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>İdarəetmə Paneli</title>
    <link rel="stylesheet" href="style.css"> </head>
<body style="background-color: #f4f6f7; font-family: 'Segoe UI', Tahoma, sans-serif; padding: 20px;">

    <div class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h1 style="color: #2c3e50; margin:0;">⚙️ İdarəetmə Paneli</h1>
            <a href="index.php" style="color: #e74c3c; text-decoration: none; font-weight: bold;">⬅ Ana Səhifəyə Qayıt</a>
        </div>
        <p style="color: #7f8c8d;">Sistemə uğurla daxil oldunuz. Aşağıdakı bölmələrdən məlumatları idarə edə bilərsiniz.</p>
        
        <div class="tab-butonlar">
            <button class="tab-btn aktif" onclick="sekmeAc(event, 'Sifarisler')">📦 Sifarişlər</button>
            <button class="tab-btn" onclick="sekmeAc(event, 'Istifadeciler')">👥 İstifadəçilər</button>
        </div>

        <div id="Sifarisler" class="tab-icerik" style="display: block;">
            <h2>Gələn Sifarişlər</h2>
            <table class="admin-tablo">
                <tr>
                    <th>ID</th>
                    <th>Sifariş Detayları</th>
                    <th>Toplam (AZN)</th>
                    <th>Tarix</th>
                </tr>
                <?php
                $sifaris_sorgu = "SELECT * FROM sifarisler ORDER BY id DESC";
                $sifaris_sonuc = $baglanti->query($sifaris_sorgu);
                
                if($sifaris_sonuc->num_rows > 0) {
                    while($satir = $sifaris_sonuc->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $satir['id'] . "</td>";
                        echo "<td>" . $satir['detaylar'] . "</td>";
                        echo "<td>" . $satir['toplam_tutar'] . "</td>";
                        echo "<td>" . $satir['tarih'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Hələ heç bir sifariş yoxdur.</td></tr>";
                }
                ?>
            </table>
        </div>

        <div id="Istifadeciler" class="tab-icerik">
            <h2>Qeydiyyatdan Keçən İstifadəçilər</h2>
            <table class="admin-tablo">
                <tr>
                    <th>ID</th>
                    <th>Ad və Soyad</th>
                    <th>E-poçt (Email)</th>
                    <th>Kriptolanmış Şifrə (Hash)</th>
                </tr>
                <?php
                $istifadeci_sorgu = "SELECT * FROM istifadeciler ORDER BY id DESC";
                $istifadeci_sonuc = $baglanti->query($istifadeci_sorgu);
                
                if($istifadeci_sonuc && $istifadeci_sonuc->num_rows > 0) {
                    while($satir = $istifadeci_sonuc->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $satir['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($satir['ad_soyad']) . "</td>";
                        echo "<td>" . htmlspecialchars($satir['email']) . "</td>";
                        // Siber güvenlik detayı: Şifre uzun olduğu için class atadık
                        echo "<td class='kriptolu-sifre'>" . $satir['sifre'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Sistemdə hələ heç bir istifadəçi yoxdur.</td></tr>";
                }
                ?>
            </table>
        </div>

    </div>

    <script>
        function sekmeAc(evt, sekmeAdi) {
            var i, tabicerik, tabbtn;
            
            // Tüm içerikleri gizle
            tabicerik = document.getElementsByClassName("tab-icerik");
            for (i = 0; i < tabicerik.length; i++) {
                tabicerik[i].style.display = "none";
            }
            
            // Tüm butonların aktiflik durumunu kaldır
            tabbtn = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tabbtn.length; i++) {
                tabbtn[i].className = tabbtn[i].className.replace(" aktif", "");
            }
            
            // Tıklanan sekmeyi göster ve butonunu aktif yap
            document.getElementById(sekmeAdi).style.display = "block";
            evt.currentTarget.className += " aktif";
        }
    </script>
</body>
</html>