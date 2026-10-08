<?php include("connection.php");
$table = "data_login"; 
$id_login = $_GET["id_login"]; 
$photo = $_FILES["photo"]["name"]; 
$nama_lengkap = $_POST["nama_lengkap"]; 
$jenis_kelamin = $_POST["jenis_kelamin"]; 
$alamat = $_POST["alamat"]; 
$bio = $_POST["bio"]; 
$email = $_POST["email"]; 
$email = $_POST["email"]; 

$target_dir = "uploads/"; 
$target_file = $target_dir . basename($_FILES["photo"]["name"]); 
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); 

if($_GET['act']=='insert'){ 
if($imageFileType != "jpeg" && $imageFileType != "jpg" && $imageFileType != "png"){ 
echo '<script>alert("Sorry, only  jpeg , jpg , png  files are allowed"); window.history.go(-1);</script>'; 
}else if ($_FILES["photo"]["size"] > 30000000){ 
echo '<script>alert("Sorry, your file is too large"); window.history.go(-1);</script>';  
}else{ 
		if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)){ 
			$sql = "INSERT INTO `$table` (`photo`, 
`nama_lengkap`, 
`jenis_kelamin`, 
`alamat`, 
`bio`, 
`email`, 
`password`) 
			VALUES ('$photo', 
'$nama_lengkap', 
'$jenis_kelamin', 
'$alamat', 
'$bio', 
'$email', 
'$password')"; 
			if ($conn->query($sql) === TRUE) { 
			echo '<script>alert("Data execute successfully"); window.history.go(-1);</script>'; 
			}else{ 
			echo '<script>alert("Data error execute"); window.history.go(-1);</script>'; 
			} 
		} 
		$conn->close(); 
} 
}else if($_GET['act']=='update'){ 
if ($_FILES["photo"]["size"] > 0){ 
if($imageFileType != "jpeg" && $imageFileType != "jpg" && $imageFileType != "png"){ 
echo '<script>alert("Sorry, only  jpeg , jpg , png  files are allowed"); window.history.go(-1);</script>'; 
}else if ($_FILES["photo"]["size"] > 30000000){ 
echo '<script>alert("Sorry, your file is too large"); window.history.go(-1);</script>';  
}else{ 
		if(move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)){ 
			$sql = "UPDATE `$table` SET `photo` = '$photo', 
`nama_lengkap` = '$nama_lengkap', 
`jenis_kelamin` = '$jenis_kelamin', 
`alamat` = '$alamat', 
`bio` = '$bio', 
`email` = '$email', 
`password` = '$password' WHERE `id_login` = '$id_login'"; 
			if ($conn->query($sql) === TRUE) { 
			echo '<script>alert("Data execute successfully"); window.history.go(-1);</script>'; 
			}else{ 
			echo '<script>alert("Data error execute"); window.history.go(-1);</script>'; 
			} 
			$conn->close(); 
		} 
} 
}else{ 
		$sql = "UPDATE `$table` SET `nama_lengkap` = '$nama_lengkap', 
`jenis_kelamin` = '$jenis_kelamin', 
`alamat` = '$alamat', 
`bio` = '$bio', 
`email` = '$email', 
`password` = '$password' WHERE `id_login` = '$id_login'"; 
		if ($conn->query($sql) === TRUE) { 
		echo '<script>alert("Data execute successfully"); window.history.go(-1);</script>'; 
		}else{ 
		echo '<script>alert("Data error execute"); window.history.go(-1);</script>'; 
		} 
		$conn->close(); 
} 
}else if($_GET['act']=='delete'){ 
$result = $conn->query("SELECT * FROM `$table` WHERE `id_login` = '$id_login'"); 
$row = $result->fetch_assoc(); 
if(file_exists("uploads/$row[photo]")){ 
unlink("uploads/$row[photo]"); 
} 
$sql = "DELETE FROM `$table` WHERE `id_login` = '$id_login'"; 
if ($conn->query($sql) === TRUE) { 
echo '<script>alert("Data execute successfully"); window.history.go(-1);</script>'; 
}else{ 
echo '<script>alert("Data error execute"); window.history.go(-1);</script>'; 
}
$conn->close(); }
 ?>