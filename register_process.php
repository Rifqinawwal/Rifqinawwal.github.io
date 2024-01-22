<?php
// File: register_process.php
session_start();
// Fungsi koneksi ke database
function koneksiDatabase()
{
    $host = "localhost"; // Ganti dengan nama host database Anda
    $username = "root"; // Ganti dengan nama pengguna database Anda
    $password = ""; // Ganti dengan kata sandi database Anda
    $database = "setda"; // Ganti dengan nama database Anda

    $koneksi = new mysqli($host, $username, $password, $database);

    // Periksa koneksi
    if ($koneksi->connect_error) {
        die("Koneksi ke database gagal: " . $koneksi->connect_error);
    }

    return $koneksi;
}

// Fungsi untuk memproses pendaftaran pengguna
function prosesPendaftaran($username, $password, $confirm_password)
{
    // Validasi password
    if ($password !== $confirm_password) {
        die("Konfirmasi password tidak sesuai.");
    }

    $koneksi = koneksiDatabase();

    // Lindungi dari serangan SQL Injection
    $username = $koneksi->real_escape_string($username);
    $password = $koneksi->real_escape_string($password);

    // Query untuk memeriksa apakah username sudah digunakan
    $queryCekUsername = "SELECT * FROM nama_tabel_pengguna WHERE username='$username'";
    $resultCekUsername = $koneksi->query($queryCekUsername);

    if ($resultCekUsername->num_rows > 0) {
        die("Username sudah digunakan. Silakan pilih username lain.");
    }

    // Query untuk menyimpan pengguna baru ke database
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hashing password
    $queryTambahPengguna = "INSERT INTO nama_tabel_pengguna (username, password) VALUES ('$username', '$hashedPassword')";
    $resultTambahPengguna = $koneksi->query($queryTambahPengguna);

    if ($resultTambahPengguna) {
        header("Location: index.php");

    } else {
        echo "Pendaftaran gagal. Silakan coba lagi.";
    }

    // Tutup koneksi database
    $koneksi->close();
}

// Memeriksa apakah form pendaftaran telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Memproses pendaftaran
    prosesPendaftaran($username, $password, $confirm_password);
}
?>
