<?php
// Mulai sesi
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hapus sesi dari penyimpanan
session_destroy();

// Redirect ke halaman login atau halaman lain yang sesuai
header("Location: login_form.php");
exit();
?>
