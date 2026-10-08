<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "menpuppk15";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
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

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
"Connected successfully";
?>