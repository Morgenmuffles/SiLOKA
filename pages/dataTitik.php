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
    $nama_titik = trim($_POST['nama_titik']);
    
    if ($id) {
        $stmt = $conn->prepare("UPDATE data_titik SET nama_titik=? WHERE id_titik=?");
        $stmt->bind_param("si", $nama_titik, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO data_titik (nama_titik) VALUES (?)");
        $stmt->bind_param("s", $nama_titik);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='./?folder=pages&file=dataTitik';</script>";
    }    
    $stmt->close();
    $conn->close();
}

if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];

    if (!is_numeric($id_hapus)) {
        die("ID tidak valid.");
    }

    $stmt = $conn->prepare("DELETE FROM data_titik WHERE id_titik = ?");
    $stmt->bind_param("i", $id_hapus);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "<script>alert('Data berhasil dihapus!'); window.location='./?folder=pages&file=dataTitik';</script>";
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
    <title>Form Input Titik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container-fluid mt-5">
    <h2 class="text-center">Input Data Titik</h2>
    <div class="card p-4">
    <form method="POST">
        <input type="hidden" name="id" id="id">
        <div class="mb-3">
            <label>Nama Titik :</label>
            <input type="text" name="nama_titik" id="nama_titik" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Simpan</button>
    </form>
</div>

    <h2 class="text-center mt-5">Data Titik</h2>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari Titik...">
    <div class="table-responsive">
    <table id="tabletitik" class="table table-bordered">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Titik/KM</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody id="titikTable">
    <?php
    $result = $conn->query("SELECT * FROM data_titik");
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $data_json = htmlspecialchars(json_encode($row, JSON_HEX_APOS | JSON_HEX_QUOT), ENT_QUOTES, 'UTF-8');
        ?>
        <tr>
            <td><?= $no; ?></td>
            <td><?= htmlspecialchars($row['nama_titik'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
                <button class="btn btn-warning btn-sm" onclick='editData(<?= $data_json; ?>)'>Edit</button>
                <a href="index.php?folder=pages&file=dataTitik&hapus=<?= $row['id_titik']; ?>"
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
    document.getElementById("id").value = data.id_titik;
    document.getElementById("nama_titik").value = data.nama_titik;
    window.scrollTo({ top: 0, behavior: "smooth" });
}
</script>
<script>
$(document).ready(function () {
    $('#tabletitik').DataTable({
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
        $("#titikTable tr").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>
</body>
</html>
