<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Swakelola</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
<div class="card">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-1">Input Swakelola</h3>
    </div>
    <div class="table-responsive mb-1">
        
        <table id="tableswakelola" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>No</th>
            <th>Data Kegiatan</th>
            <th>Titik KM</th>
            <th>Titik Meter</th>
            <th>Lajur</th>
            <th>Foto 0%</th>
            <th>Foto 50%</th>
            <th>Foto 100%</th>
            <th>Preview</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $result = $conn->query("SELECT f.*, k.data_kegiatan, t.nama_titik FROM data_swakelola f 
                                JOIN data_kegiatan k ON f.id_kegiatan = k.id_kegiatan 
                                JOIN data_titik t ON f.id_titik = t.id_titik ORDER BY f.id_foto DESC"); 
        $no = 1; 
        while ($row = $result->fetch_assoc()) { 
            echo "<tr> 
                <td>{$no}</td> 
                <td>{$row['data_kegiatan']}</td> 
                <td>{$row['nama_titik']}</td> 
                <td>{$row['titik_meter']}</td> 
                <td>{$row['lajur']}</td> 
                <td><img src='{$row['lampiran1']}' width='80' onclick='showImagePreview(\"{$row['lampiran1']}\")'></td> 
                <td><img src='{$row['lampiran2']}' width='80' onclick='showImagePreview(\"{$row['lampiran2']}\")'></td> 
                <td><img src='{$row['lampiran3']}' width='80' onclick='showImagePreview(\"{$row['lampiran3']}\")'></td> 
                <td><button class='btn btn-primary btn-sm' onclick='previewPDF({$row['id_foto']})'>Preview</button></td>
            </tr>"; 
            $no++; 
        } 
        ?>
    </tbody>
</table>

    </div>

    <!-- Preview PDF -->
    <div class="card mt-4">
        <div id="pdfPreviewContainer" style="display: none; text-align: center; margin-top: 20px;">
        <h4>Preview PDF</h4>
        <iframe id="pdfPreviewFrame" style="width: 80%; height: 500px; border: 1px solid #ccc;"></iframe>
        </div>
    </div>


    <script>

function previewPDF(id_foto) {
    console.log("Preview PDF dipanggil dengan id_foto:", id_foto);

    if (!id_foto) {
        alert("ID foto tidak valid!");
        return;
    }

    // Cek apakah ID valid (bukan string kosong atau undefined)
    if (isNaN(id_foto)) {
        alert("ID harus berupa angka!");
        return;
    }

    var pdfUrl = "pages/generate_pdf.php?id_foto=" + id_foto;
    console.log("Mengambil PDF dari:", pdfUrl);

    var pdfFrame = document.getElementById("pdfPreviewFrame");
    if (!pdfFrame) {
        alert("Elemen preview PDF tidak ditemukan!");
        return;
    }

    pdfFrame.src = pdfUrl;

    document.getElementById("pdfPreviewContainer").style.display = "block";
    document.getElementById("pdfPreviewContainer").scrollIntoView({ behavior: "smooth" });
}

        $(document).ready(function () {
            $('#tableswakelola').DataTable();
        });

        function previewPDF(id_foto) {
            // Ubah URL ini agar sesuai dengan endpoint yang menghasilkan PDF
            var pdfUrl = "pages/generate_pdf.php?id_foto=" + id_foto;

            // Set sumber PDF di dalam iframe
            document.getElementById("pdfPreviewFrame").src = pdfUrl;

            // Tampilkan div yang sebelumnya disembunyikan
            document.getElementById("pdfPreviewContainer").style.display = "block";

            // Scroll ke bagian preview
            document.getElementById("pdfPreviewContainer").scrollIntoView({ behavior: "smooth" });
        }

        function showImagePreview(source) {
            var previewImage = document.getElementById("previewImage");
            previewImage.src = source;
            var imageModal = new bootstrap.Modal(document.getElementById("imagePreviewModal"));
            imageModal.show();
        }
    </script>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Preview Foto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="previewImage" src="" class="img-fluid" style="max-height: 80vh;">
                </div>
            </div>
        </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
