<?php
include 'db.php';

// JavaScript'ten gelen sepet verisini alıyoruz
$veri = json_decode(file_get_contents("php://input"), true);

if($veri && !empty($veri['sepet'])) {
    $sepet = $veri['sepet'];
    $toplam = $veri['toplam'];
    
    // Sepetteki formaların isimlerini yan yana yazdırıyoruz
    $detaylar = "";
    foreach($sepet as $urun) {
        $detaylar .= $urun['isim'] . " (" . $urun['fiyat'] . " AZN), ";
    }
    
    // Veritabanına kaydetme komutu
    $sorgu = "INSERT INTO sifarisler (detaylar, toplam_tutar) VALUES ('$detaylar', '$toplam')";
    
    if($baglanti->query($sorgu) === TRUE) {
        echo json_encode(["durum" => "basarili"]);
    } else {
        echo json_encode(["durum" => "hata"]);
    }
} else {
    echo json_encode(["durum" => "bos"]);
}
?>