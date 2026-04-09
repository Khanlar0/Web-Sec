<?php
session_start();
session_destroy(); // Tüm giriş bilgilerini siler (Çıkış yapar)
header("Location: index.php"); // Ana sayfaya geri gönderir
exit;
?>