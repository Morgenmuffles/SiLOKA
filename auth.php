<?php
session_start();
include("koneksi.php"); // Ensure database connection is included

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $query = mysqli_query($conn, "SELECT * FROM data_login WHERE id_login='$id_login'");
    $data_login = mysqli_fetch_assoc($query);
} else {
    $data_login = ['photo' => 'default.png', 'nama_lengkap' => 'Guest', 'jabatan' => ''];
}
?>
