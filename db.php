<?php
$baglanti = new mysqli("localhost", "root", "", "forma_dukkani");
if ($baglanti->connect_error) {
    die("Bağlantı hatası: " . $baglanti->connect_error);
}
?>