<?php 
include('koneksi.php');
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header('Location: login.html');
    exit();
}

// Ambil data user berdasarkan sesi login
$stmt = $conn->prepare("SELECT * FROM `data_login` WHERE `id_login` = ?");
$stmt->bind_param("i", $_SESSION['id_login']);
$stmt->execute();
$result = $stmt->get_result();
$data_login = $result->fetch_assoc();
$stmt->close();

// Sanitasi input folder & file
$folder = isset($_GET['folder']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['folder']) : 'pages';
$file = isset($_GET['file']) ? preg_replace('/[^a-zA-Z0-9_-]/', '', $_GET['file']) : 'dashboard';

// Mulai HTML
include('header.php'); 
include('pages/navbar.php'); 
include('pages/sidebar.php'); 
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <?php
    $filePath = "$folder/$file.php";

    echo "<!-- Debug: Mencoba memuat $filePath -->"; // Tambahkan ini

    if (file_exists($filePath)) {
        // Batasi akses modul hanya untuk admin
        if (isset($folder) && strpos($folder, 'modules') !== false && $data_login['jabatan'] != 'Admin') {
            include("pages/403.php"); // Halaman akses ditolak
        } else {
            include($filePath);
        }
    } else {
        include("pages/404.html"); // Halaman tidak ditemukan
    }
    ?>
</div>
<!-- /.content-wrapper -->

<?php include('pages/footer.php'); ?>
