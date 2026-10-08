<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

<!-- jQuery dan DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

<?php
include 'koneksi.php';


if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $id = $_POST['id'] ?? null;
    $data_kegiatan = trim($_POST['data_kegiatan']);
    $akronim = trim($_POST['akronim']);
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE data_kegiatan SET data_kegiatan=?, akronim=? WHERE id_kegiatan=?");
        $stmt->bind_param("ssi", $data_kegiatan, $akronim, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO data_kegiatan (data_kegiatan, akronim) VALUES (?, ?)");
        $stmt->bind_param("ss", $data_kegiatan, $akronim);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='./?folder=pages&file=dataKegiatan';</script>";
    }    
    $stmt->close();
    $conn->close();
}


if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    if (!is_numeric($id_hapus)) {
        die("ID tidak valid.");
    }

    $stmt = $conn->prepare("DELETE FROM data_kegiatan WHERE id_kegiatan = ?");
    $stmt->bind_param("i", $id_hapus);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Data berhasil dihapus!'); window.location='./?folder=pages&file=dataKegiatan';</script>";
        } else {
            echo "<script>alert('Data tidak ditemukan atau gagal dihapus.');</script>";
        }
    } else {
        echo "<script>alert('Gagal menghapus data: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Input Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2 class="text-center">Input Data Kegiatan</h2>
    <div class="card p-4">
    <form method="POST">
        <input type="hidden" name="id" id="id">
        <div class="mb-3">
            <label>Data Kegiatan :</label>
            <input type="text" name="data_kegiatan" id="data_kegiatan" class="form-control" required>
            <label>Akronim :</label>
            <input type="text" name="akronim" id="akronim" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan</button>
    </form>
</div>

    <h2 class="text-center mt-5">Data Kegiatan</h2>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari Kegiatan...">
    <div class="table-responsive">
    <table id="tablekegiatan" class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Data Kegiatan</th>
            <th>Akronim</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="kegiatanTable">
    <?php
    $result = $conn->query("SELECT * FROM data_kegiatan");
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $data_json = htmlspecialchars(json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
        ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= htmlspecialchars($row['data_kegiatan'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($row['akronim'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='editData(<?= $data_json; ?>)'>Edit</button>
                <a href="index.php?folder=pages&file=dataKegiatan&hapus=<?= $row['id_kegiatan']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                   Hapus
                </a>
            </td>
        </tr>
        <?php
        $no++;
    }
    ?>
    </tbody>
</table>


</div>

<script>
function editData(data) {
    document.getElementById("id").value = data.id_kegiatan;
    document.getElementById("data_kegiatan").value = data.data_kegiatan;
    window.scrollTo({ top: 0, behavior: "smooth" });
}
</script>
<script>
$(document).ready(function () {
    $('#tablekegiatan').DataTable({
        "paging": true,        // Aktifkan paginasi
        "lengthMenu": [10, 25, 50, 100], // Jumlah baris per halaman
        "ordering": true,      // Aktifkan sorting
        "info": true,         // Tampilkan info jumlah data
        "searching": true,     // Aktifkan pencarian
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
});
</script>

<script>
$(document).ready(function () {
    $("#searchInput").on("keyup", function () {
        var value = $(this).val().toLowerCase();
        $("#kegiatanTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
</body>
</html>
