<?php
include 'koneksi.php';

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $stmt = $conn->prepare("SELECT COUNT(*) FROM data_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($jumlah);
    $stmt->fetch();
    $stmt->close();

    echo ($jumlah > 0) ? "exist" : "available";
}
?>
