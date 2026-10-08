<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- jQuery dan DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<?php

include 'koneksi.php';

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM data_login WHERE id_login = ?");
    $stmt->bind_param("i", $id_hapus);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil dihapus!'); window.location='?folder=pages&file=inputUser';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data.');</script>";
    }
    $stmt->close();
}


// Periksa apakah user sudah login
if (!isset($_SESSION['id_login'])) {
    header("Location: login.html");
    exit();
}

// Ambil data user dari database
$stmt = $conn->prepare("SELECT * FROM data_login WHERE id_login = ?");
$stmt->bind_param("i", $_SESSION['id_login']);
$stmt->execute();
$result = $stmt->get_result();
$data_login = $result->fetch_assoc();
$stmt->close();

// Cek apakah user bukan Admin
if ($data_login['jabatan'] != 'Admin') {
    header("Location: ./?folder=pages&file=403"); // Redirect ke halaman akses ditolak
    exit();
}

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    $id = $_POST['id'] ?? null;
    $email = trim($_POST['email']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $password = trim($_POST['password']);
    $jabatan = trim($_POST['jabatan']);
    $alamat = trim($_POST['alamat']);
    $bio = trim($_POST['bio']);
    $jenis_kelamin = trim($_POST['jenis_kelamin']);
    
    $foto = $_POST['foto_lama'];
    if (!empty($_FILES['foto']['name'])) {
        $foto = "dist/img/" . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], $foto);
    }

    if ($id) {
        // **Cek apakah username sudah digunakan oleh user lain**
        $cek_email_stmt = $conn->prepare("SELECT 1 FROM data_login WHERE email = ? AND id_login != ?");
        $cek_email_stmt->bind_param("si", $email, $id);
        $cek_email_stmt->execute();
        $cek_email_stmt->store_result();
    
        if ($cek_email_stmt->num_rows > 0) {
            echo "<script>alert('username sudah digunakan oleh user lain! gunakan username lain.'); window.history.back();</script>";
            exit();
        }
        $cek_email_stmt->close();
    
        // Jika password diisi, update dengan hash baru. Jika tidak, gunakan password lama.
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE data_login SET email=?, nama_lengkap=?, jabatan=?, alamat=?, bio=?, jenis_kelamin=?, foto=?, password=? WHERE id_login=?");
            $stmt->bind_param("ssssssssi", $email, $nama_lengkap, $jabatan, $alamat, $bio, $jenis_kelamin, $foto, $hashed_password, $id);
        } else {
            $stmt = $conn->prepare("UPDATE data_login SET email=?, nama_lengkap=?, jabatan=?, alamat=?, bio=?, jenis_kelamin=?, foto=? WHERE id_login=?");
            $stmt->bind_param("sssssssi", $email, $nama_lengkap, $jabatan, $alamat, $bio, $jenis_kelamin, $foto, $id);
        }
    } else {
        // **Cek apakah username sudah ada**
        $cek_email_stmt = $conn->prepare("SELECT 1 FROM data_login WHERE email = ?");
        $cek_email_stmt->bind_param("s", $email);
        $cek_email_stmt->execute();
        $cek_email_stmt->store_result();
    
        if ($cek_email_stmt->num_rows > 0) {
            echo "<script>alert('username sudah terdaftar! gunakan username lain.'); window.history.back();</script>";
            exit();
        }
        $cek_email_stmt->close();
    
        // Jika email belum ada, lanjutkan insert
        if (empty($password)) {
            echo "<script>alert('Password tidak boleh kosong saat menambah user baru!'); window.history.back();</script>";
            exit();
        }
    
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO data_login (email, nama_lengkap, password, jabatan, alamat, bio, jenis_kelamin, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $email, $nama_lengkap, $hashed_password, $jabatan, $alamat, $bio, $jenis_kelamin, $foto);
    }
    

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='./?folder=pages&file=inputUser';</script>";
    }    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Input User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2 class="text-center">Input Data User</h2>
    <div class="card p-4">
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <div class="row">
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Username :</label>
                <input type="text" name="email" id="email" class="form-control" required>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Nama Lengkap :</label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" required>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Password: <span style="color: red; font-size: 0.85em; opacity: 0.7;">*Kosongkan jika tidak diubah.</span></label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Alamat :</label>
                <input type="text" name="alamat" id="alamat" class="form-control" required>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Keterangan :</label>
                <input type="text" name="bio" id="bio" class="form-control" required>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Jenis Kelamin :</label>
                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Pria">Pria</option>
                    <option value="Wanita">Wanita</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Jabatan :</label>
                <select name="jabatan" id="jabatan" class="form-control" required>
                    <option value="">-- Pilih --</option>
                    <option value="Admin">Admin</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-3">
                <label>Foto :</label>
                <input type="file" name="foto" id="foto" class="form-control">
                <input type="hidden" name="foto_lama" id="foto_lama">
                <br>
                <img id="preview_foto" src="" alt="Foto Profil" style="max-width: 100px; display: none;">
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan</button>
    </form>
</div>



    <h2 class="text-center mt-5">Data User</h2>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari User...">
    <div class="table-responsive">
    <table id="tableuser" class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th>Alamat</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="userTable">
        <?php
$result = $conn->query("SELECT * FROM data_login");
$no = 1;
while ($row = $result->fetch_assoc()) {
    // Pastikan foto memiliki path lengkap jika hanya nama file yang disimpan di database
    $row['foto'] = !empty($row['foto']) ? htmlspecialchars($row['foto'], ENT_QUOTES, 'UTF-8') : 'dist/img/default.jpg';

    echo "<tr>
        <td>{$no}</td>
        <td>{$row['email']}</td>
        <td>{$row['nama_lengkap']}</td>
        <td>{$row['jabatan']}</td>
        <td>{$row['alamat']}</td>
        <td>{$row['bio']}</td>
        <td>
            <button class='btn btn-warning btn-sm flex-fill' onclick='editData(". json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT) .")'>Edit</button>
            <a href='?folder=pages&file=inputUser&hapus={$row['id_login']}' class='btn btn-danger btn-sm flex-fill' 
                onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\");'>
                Hapus
            </a>
        </td>
    </tr>";
    $no++;
}
?>

        </tbody>
    </table>
</div>

<script>
function editData(data) {
    let imgPreview = document.getElementById("preview_foto");
    let fotoLama = document.getElementById("foto_lama");

    document.getElementById("id").value = data.id_login;
    document.getElementById("email").value = data.email;
    document.getElementById("nama_lengkap").value = data.nama_lengkap;
    document.getElementById("jabatan").value = data.jabatan;
    document.getElementById("alamat").value = data.alamat;
    document.getElementById("bio").value = data.bio;
    document.getElementById("jenis_kelamin").value = data.jenis_kelamin;

    console.log("Foto dari database:", data.foto); // Cek di console log

    if (data.foto && data.foto !== "null" && data.foto !== "") {
        let fotoPath = data.foto.startsWith("dist/img/") ? data.foto : "dist/img/" + data.foto;
        imgPreview.src = fotoPath;
        imgPreview.style.display = "block";
        fotoLama.value = fotoPath;
    } else {
        imgPreview.style.display = "none";
        fotoLama.value = "";
    }

    window.scrollTo({ top: 0, behavior: "smooth" });
}


</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    if (!document.getElementById("preview_foto")) {
        console.error("Elemen preview_foto tidak ditemukan di dalam HTML!");
    }
});
</script>
<script>
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#userTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
<script>
document.getElementById('foto').addEventListener('change', function(event) {
    let reader = new FileReader();
    reader.onload = function() {
        let preview = document.getElementById('preview_foto');
        preview.src = reader.result;
        preview.style.display = "block";
    }
    reader.readAsDataURL(event.target.files[0]);
});
</script>
<script>
$(document).ready(function () {
    // Inisialisasi DataTable
    $('#tableuser').DataTable({
        "paging": true,
        "lengthMenu": [10, 25, 50, 100],
        "ordering": true,
        "info": true,
        "searching": true,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(disaring dari _MAX_ total data)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Cek Email dengan AJAX
    $("#email").on("blur", function () {
        var email = $(this).val();
        if (email.length > 0) {
            $.ajax({
                url: "cek_email.php",
                type: "POST",
                data: { email: email },
                success: function (response) {
                    if (response === "exist") {
                        alert("Username sudah digunakan! Gunakan Username lain.");
                        $("#email").val(""); // Kosongkan input
                    }
                }
            });
        }
    });
});
</script>


</body>
</html>
