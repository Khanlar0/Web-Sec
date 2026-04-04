<?php
session_start();
include 'db.php'; // Veritabanı bağlantısı

if(!isset($_SESSION['giris_yapildi'])) {
    die("Bu sayfayı görüntüleme yetkiniz yok!");
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <title>Admin Paneli</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f4f4f4; }
        .kutu { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
    </style>
</head>
<body>
    <div class="kutu">
        <h1 style="color: green;">Admin Panel</h1>
        <p>Burası saytın gizli admin panelidir. Müşterilerin verdiği sifarişləri aşağıda görə bilərsiniz.</p>
        <a href="index.php" style="color: red;">Əsas Səhifəyə Qayıt</a>
        
        <h2>Gələn Sifarişlər</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Sifariş Detayları</th>
                <th>Toplam (AZN)</th>
                <th>Tarix</th>
            </tr>
            <?php
            // Siparişleri veritabanından çek ve tabloya yaz
            $sorgu = "SELECT * FROM sifarisler ORDER BY id DESC";
            $sonuc = $baglanti->query($sorgu);
            
            if($sonuc->num_rows > 0) {
                while($satir = $sonuc->fetch_assoc()) {
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
</body>
</html>