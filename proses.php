<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id_login, password FROM data_login WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        if (password_verify($password, $data['password'])) {
            session_start();
            $_SESSION['id_login'] = $data['id_login'];
            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Password salah!'); window.location='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!'); window.location='login.html';</script>";
    }
    $stmt->close();
}
?>
