<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>FormaX.az</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <div class="top-nav">
        <div class="site-logo">
             FormaX.az
        </div>
        <div class="nav-right">
            <button id="cartBtn" class="cart-btn">
                🛒 Səbət <span id="cart-count">(0)</span>
            </button>
            <a href="login.php" class="admin-box" title="Yönetici Girişi">⚙️ Admin</a>
        </div>
    </div>

    <div id="cartPanel" class="cart-panel">
        <h3>Sizin Sepətiniz</h3>
        <ul id="sepet-listesi">
            <li class="sepet-bos">Sepətiniz hazırda boşdur.</li>
        </ul>
        <h4 id="toplam-tutar">Cəmi: 0 AZN</h4>
        
        <button id="sifarisBtn" class="sifaris-btn">Sifariş Et</button>
    </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Yeni Sezon və Retro Formalar</h1>
        </div>
        
        <div class="forma-list">
            <?php
            $sorgu = "SELECT * FROM formalar";
            $sonuc = $baglanti->query($sorgu);
            
            while($satir = $sonuc->fetch_assoc()) {
                $saf_fiyat = preg_replace('/[^0-9]/', '', $satir['fiyat']);
                $takim = strtolower($satir['takim']);
                $resim_linki = "https://via.placeholder.com/300x200.png?text=Bilinmeyen+Forma"; // Varsayılan resim

                // Takım ismine göre fotoğraf eşleştirme
                if (strpos($takim, 'sabah') !== false) {
                    $resim_linki = "https://imageproxy.wolt.com/menu/menu-images/6708e6adc7582d27a10859dd/f22ba408-87b2-11ef-b5c2-0e70571d2295____2_.jpg?w=600";
                } elseif (strpos($takim, 'qarabağ') !== false || strpos($takim, 'qarabag') !== false) {
                    $resim_linki = "https://fls-9f1d25fb-59b4-45a0-a922-2d3c65bf4734.laravel.cloud/284/conversions/01KA3Y2SCGB8F8K9VGEAYSW0EZ-webp.webp";
                } elseif (strpos($takim, 'neftçi') !== false || strpos($takim, 'neftci') !== false) {
                    $resim_linki = "https://cdn.myikas.com/images/cad6653f-f1df-4bbe-a2ce-c8db43178bb0/ece991c6-4e5f-463c-af60-898f39a05e8f/1080/bnsh-1.webp";
                }

                echo "<div class='forma'>";
                echo "<img src='" . $resim_linki . "' alt='Forma' class='forma-img'>";
                echo "<h3>" . $satir['takim'] . "</h3>";
                echo "<p class='fiyat-yazisi'>Qiymət: <b>" . $satir['fiyat'] . "</b></p>";
                echo "<button class='sepete-ekle' data-isim='" . $satir['takim'] . "' data-fiyat='" . $saf_fiyat . "' data-img='" . $resim_linki . "'>Səbətə Əlavə Et</button>";
                echo "</div>";
            }
            ?>
                <div class="daha-cox-alani">
                <button id="dahaCoxBtn" class="daha-cox-btn">Daha çox forma göstər ⬇</button>
            </div>
        </div>
    </div>

    <button id="destekBtn" class="destek-btn">💬 Dəstək</button>
    <div id="destekModal" class="modal">
        <div class="modal-icerik">
            <span class="kapat">&times;</span>
            <h2>Bizə Yazın</h2>
            <p>Hər hansı bir probleminiz var? Aşağıdan bizə bildirin:</p>
            <textarea id="sorunMetni" rows="4" placeholder="Problemi buraya yazın..."></textarea><br>
            <button id="sorunGonder" class="gonder-btn">Göndər</button>
        </div>
    </div>

    <footer class="site-footer">
        <div class="footer-icerik">
            <p>&copy; 2026 FormaX.az. Bütün hüquqlar qorunur.</p>
            <p style="font-size: 12px; color: #bdc3c7;">Bu sayt tədris və sınaq məqsədi ilə yaradılmışdır.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>