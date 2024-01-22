<?php
// File: login_process.php
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

// Fungsi untuk memproses login
function prosesLogin($username, $password)
{
    $koneksi = koneksiDatabase();

    // Lindungi dari serangan SQL Injection
    $username = $koneksi->real_escape_string($username);
    $password = $koneksi->real_escape_string($password);

    // Query untuk memeriksa keberadaan pengguna dengan username dan password yang sesuai
    $query = "SELECT * FROM nama_tabel_pengguna WHERE username='$username'";
    $result = $koneksi->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashedPasswordDB = $row['password'];

            // Periksa kata sandi dengan password_verify
            if (password_verify($password, $hashedPasswordDB)) {
                // Login berhasil
                header("Location: index.php");
            } else {
                // Kata sandi tidak sesuai
                echo "Login gagal. Periksa kembali username dan password Anda.";
            }
        } else {
            // Pengguna tidak ditemukan
            echo "Login gagal. Periksa kembali username dan password Anda.";
        }
    } else {
        // Tampilkan pesan kesalahan query
        echo "Query error: " . $koneksi->error;
    }

    // Tutup koneksi database
    $koneksi->close();
}

// Memeriksa apakah form login telah dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Memproses login
    prosesLogin($username, $password);
}
?>
