<!DOCTYPE html>
<?php
if (isset($_GET['hapus'])) {
    require_once('koneksi.php'); // Sesuaikan dengan lokasi file koneksi
    
    $id_foto = intval($_GET['hapus']); // Pastikan ID berupa angka

    // Ambil data foto yang akan dihapus
    $query = $conn->prepare("SELECT lampiran1, lampiran2, lampiran3 FROM data_swakelola WHERE id_foto = ?");
    $query->bind_param("i", $id_foto);
    $query->execute();
    $result = $query->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Hapus file foto dari server
        foreach (['lampiran1', 'lampiran2', 'lampiran3'] as $foto) {
            if (!empty($row[$foto]) && file_exists($row[$foto])) {
                unlink($row[$foto]); // Hapus file dari folder
            }
        }

        // Hapus data dari database
        $deleteQuery = $conn->prepare("DELETE FROM data_swakelola WHERE id_foto = ?");
        $deleteQuery->bind_param("i", $id_foto);
        $deleteQuery->execute();

        if ($deleteQuery->affected_rows > 0) {
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='index.php?folder=pages&file=inputSwakelola';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menghapus data!');</script>";
        }
    } else {
        echo "<script>alert('Data tidak ditemukan!');</script>";
    }
}
?>



<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Input Swakelola</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <style>
    .preview-box {
      width: 333px; /* Lebar sesuai konversi dari 8.8 cm */
      height: 212px; /* Tinggi sesuai konversi dari 5.6 cm */
      border: 1px solid #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden; /* Agar gambar tidak keluar dari kotak */
      position: relative;
    }
    .preview-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      position: absolute;
    }
  </style>
</head>
<body>
  <!-- Form Input Swakelola -->
  <div class="card">
    <div class="card-header bg-primary text-white">
      <h3 class="mb-0">Input Swakelola</h3>
    </div>
    <div class="card-body">
      <form id="formSwakelola" action="pages/upload.php" method="POST" enctype="multipart/form-data">
        <!-- Untuk edit -->
        <input type="hidden" name="id_foto" id="id_foto">
        
        <!-- Pilih Kegiatan -->
        <div class="form-group">
          <label>Pilih Kegiatan:</label>
          <select name="id_kegiatan" id="id_kegiatan" class="form-control" required onchange="generateKodeSwakelola()">
            <option value="" disabled selected>-- Pilih Kegiatan --</option>
            <?php
              $result = $conn->query("SELECT * FROM data_kegiatan");
              while ($row = $result->fetch_assoc()) {
                // Misalnya kita ingin menyimpan akronim di attribute data-akronim
                echo "<option value='{$row['id_kegiatan']}' data-akronim='{$row['akronim']}'>{$row['data_kegiatan']}</option>";
              }
            ?>
          </select>
        </div>
        
        <!-- Pilih Titik KM -->
        <div class="form-group">
          <label>Pilih Titik/KM:</label>
          <select name="id_titik" id="id_titik" class="form-control" required onchange="generateKodeSwakelola()">
            <option value="" disabled selected>-- Pilih Titik KM --</option>
            <?php
              $result = $conn->query("SELECT * FROM data_titik");
              while ($row = $result->fetch_assoc()) {
                // Simpan nama_titik di data attribute untuk digunakan pada kode swakelola
                echo "<option value='{$row['id_titik']}' data-namatitik='{$row['nama_titik']}'>{$row['nama_titik']}</option>";
              }
            ?>
          </select>
        </div>
        
        <!-- Input Titik Meter -->
        <div class="form-group">
          <label>Masukkan Titik Meter:</label>
          <input type="number" name="titik_meter" id="titik_meter" class="form-control" required placeholder="Masukkan titik meter" oninput="generateKodeSwakelola()">
        </div>
        
        <!-- Pilih Lajur -->
        <div class="form-group">
          <label>Pilih Lajur:</label>
          <select name="lajur" id="lajur" class="form-control" required>
            <option value="" disabled selected>-- Pilih Lajur --</option>
            <option value="L">L</option>
            <option value="R">R</option>
                      </select>
        </div>
        
        <!-- Input Kode Swakelola (otomatis) -->
        <div class="form-group">
          <label>Kode Swakelola:</label>
          <input type="text" name="kode_swakelola" id="kode_swakelola" class="form-control" readonly>
        </div>
        
        <!-- Upload Foto -->
        <label>Upload Foto:</label>
        <div class="row">
          <div class="col-md-4">
            <div class="preview-box" id="preview1">
              <img src="" id="img1" style="display:none;" onclick="showImagePreview('img1')">
              <span class="preview-text">0%</span>
            </div>
            <input type="file" name="lampiran[]" accept="image/*" class="form-control" id="file1" onchange="previewImage(event, 'img1', 'preview1')">
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="resetInput('file1', 'img1', 'preview1')">Hapus</button>
          </div>

          <div class="col-md-4">
            <div class="preview-box" id="preview2">
              <img src="" id="img2" style="display:none;" onclick="showImagePreview('img2')">
              <span class="preview-text">50%</span>
            </div>
            <input type="file" name="lampiran[]" accept="image/*" class="form-control" id="file2" onchange="previewImage(event, 'img2', 'preview2')">
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="resetInput('file2', 'img2', 'preview2')">Hapus</button>
          </div>

          <div class="col-md-4">
            <div class="preview-box" id="preview3">
              <img src="" id="img3" style="display:none;" onclick="showImagePreview('img3')">
              <span class="preview-text">100%</span>
            </div>
            <input type="file" name="lampiran[]" accept="image/*" class="form-control" id="file3" onchange="previewImage(event, 'img3', 'preview3')">
            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="resetInput('file3', 'img3', 'preview3')">Hapus</button>
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
      </form>
    </div>
  </div>
  
  <!-- Tabel Data Swakelola -->
  <div class="card mt-3">
    <div class="card-body">
      <h4>Data Swakelola</h4>
      <div class="table-responsive">
      <table id="tableswakelola" class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>No</th>
      <th>Data Kegiatan</th>
      <th>Lokasi Titik/KM</th>
      <th>Titik Meter</th>
      <th>Lajur</th>
      <th>Kode Swakelola</th>
      <th>Foto 0%</th>
      <th>Foto 50%</th>
      <th>Foto 100%</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php 
    $result = $conn->query("SELECT f.*, k.data_kegiatan, t.nama_titik FROM data_swakelola f 
                            JOIN data_kegiatan k ON f.id_kegiatan = k.id_kegiatan 
                            JOIN data_titik t ON f.id_titik = t.id_titik ORDER BY f.id_foto DESC"); 
    $no = 1; 
    while ($row = $result->fetch_assoc()) { 
        $data_json = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8'); 
        ?>
        <tr> 
            <td><?= $no ?></td>
            <td><?= htmlspecialchars($row['data_kegiatan']) ?></td>
            <td><?= htmlspecialchars($row['nama_titik']) ?></td>
            <td><?= htmlspecialchars($row['titik_meter']) ?></td>
            <td><?= htmlspecialchars($row['lajur']) ?></td>
            <td><?= htmlspecialchars($row['kode_swakelola']) ?></td>
            <td><img src="<?= htmlspecialchars($row['lampiran1']) ?>" width="80" onclick="showImagePreview('<?= htmlspecialchars($row['lampiran1']) ?>')"></td>
            <td><img src="<?= htmlspecialchars($row['lampiran2']) ?>" width="80" onclick="showImagePreview('<?= htmlspecialchars($row['lampiran2']) ?>')"></td>
            <td><img src="<?= htmlspecialchars($row['lampiran3']) ?>" width="80" onclick="showImagePreview('<?= htmlspecialchars($row['lampiran3']) ?>')"></td>
            <td>
              <button class="btn btn-warning btn-sm w-100 mb-1" onclick='editData(<?= $data_json ?>)'>Edit</button> 
              <a href="index.php?folder=pages&file=inputSwakelola&hapus=<?= htmlspecialchars($row['id_foto']) ?>" 
                  class="btn btn-danger btn-sm w-100 mb-1" 
                  onclick="return confirm('Yakin ingin menghapus?');">
                  Hapus
              </a> 
              <a href="index.php?folder=pages&file=downloadzip&id=<?= htmlspecialchars($row['id_foto']) ?>" 
                  class="btn btn-success btn-sm w-100">
                  Download
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
    </div>
  </div>
  
  <!-- Bootstrap JS & DataTables JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  
  <script>
    $(document).ready(function () { 
      $('#tableswakelola').DataTable({
          "paging": true,
          "searching": true,
          "ordering": true,
          "info": true
      }); 
    });
  
    // Fungsi untuk meng-generate Kode Swakelola
    function generateKodeSwakelola() {
    var kodeInput = document.getElementById('kode_swakelola');
    
    var kegiatanSelect = document.getElementById('id_kegiatan');
    var selectedKegiatan = kegiatanSelect.options[kegiatanSelect.selectedIndex];
    var akronim = selectedKegiatan ? selectedKegiatan.getAttribute('data-akronim') : '';
    
    var titikSelect = document.getElementById('id_titik');
    var selectedTitik = titikSelect.options[titikSelect.selectedIndex];
    var namaTitik = selectedTitik ? selectedTitik.getAttribute('data-namatitik') : '';
    
    var titikMeter = document.getElementById('titik_meter').value;
    var lajur = document.getElementById('lajur').value;
    
    if (!akronim || !namaTitik || !titikMeter || !lajur) {
        kodeInput.value = '';
        return;
    }

    var today = new Date();
    var tanggal = today.getDate().toString().padStart(2, '0');
    var bulan = (today.getMonth() + 1).toString().padStart(2, '0');
    var tahun = today.getFullYear().toString().slice(-2);

    var duaKarakter = namaTitik.slice(-2).toUpperCase();

    var kode = `${akronim}${tanggal}${bulan}${tahun}${duaKarakter}+${titikMeter}${lajur}`;

    kodeInput.value = kode;
}


// Tambahkan event listener agar kode selalu terupdate otomatis
document.getElementById("id_kegiatan").addEventListener("change", generateKodeSwakelola);
document.getElementById("id_titik").addEventListener("change", generateKodeSwakelola);
document.getElementById("titik_meter").addEventListener("input", generateKodeSwakelola);
document.getElementById("lajur").addEventListener("change", generateKodeSwakelola);

    // Fungsi untuk menampilkan data ke form edit
    function editData(data) {
      document.getElementById("id_foto").value = data.id_foto; 
      document.getElementById("id_kegiatan").value = data.id_kegiatan;
      document.getElementById("id_titik").value = data.id_titik;
      document.getElementById("titik_meter").value = data.titik_meter;
      document.getElementById("lajur").value = data.lajur;
  
      setPreviewImage("preview1", data.lampiran1, "0%");
      setPreviewImage("preview2", data.lampiran2, "50%");
      setPreviewImage("preview3", data.lampiran3, "100%");
  
      generateKodeSwakelola(); // Perbarui kode swakelola setelah edit
  
      window.scrollTo({ top: 0, behavior: "smooth" });
    }
  
    function setPreviewImage(previewId, imageUrl, defaultText = "No Image") {
      var previewBox = document.getElementById(previewId);
      
      // Cari elemen <img> dan <span> jika sudah ada
      let previewImg = previewBox.querySelector("img");
      let previewText = previewBox.querySelector(".preview-text");
  
      if (!previewImg) {
        previewImg = document.createElement("img");
        previewImg.style.maxWidth = "100%";
        previewImg.style.maxHeight = "150px";
        previewImg.style.display = "none";
        previewBox.appendChild(previewImg);
      }
  
      if (!previewText) {
        previewText = document.createElement("span");
        previewText.classList.add("preview-text");
        previewBox.appendChild(previewText);
      }
  
      if (imageUrl && imageUrl.trim() !== '') {
        previewImg.src = imageUrl;
        previewImg.style.display = "block";
        previewText.style.display = "none";
      } else {
        previewImg.src = "";
        previewImg.style.display = "none";
        previewText.style.display = "block";
        previewText.innerText = defaultText;
      }
    }
  
    function previewImage(event, imgId, previewBoxId) {
      var reader = new FileReader();
      var imgElement = document.getElementById(imgId);
      var previewBox = document.getElementById(previewBoxId);
      var previewText = previewBox.querySelector('.preview-text');

      if (event.target.files.length > 0) {
        reader.onload = function () {
          imgElement.src = reader.result;
          imgElement.style.display = "block";
          previewText.style.display = "none";
        };
        reader.readAsDataURL(event.target.files[0]);
      } else {
        resetInput(imgId, previewBoxId);
      }
    }

  
    function showImagePreview(source) {
        var previewImage = document.getElementById("previewImage");

        if (document.getElementById(source)) {
          previewImage.src = document.getElementById(source).src;
        } else {
          previewImage.src = source;
        }

        var imageModal = new bootstrap.Modal(document.getElementById("imagePreviewModal"));
        imageModal.show();
      }

      function resetInput(fileId, imgId, previewBoxId) {
        var fileInput = document.getElementById(fileId);
        var imgElement = document.getElementById(imgId);
        var previewBox = document.getElementById(previewBoxId);
        var previewText = previewBox.querySelector('.preview-text');

        fileInput.value = "";
        imgElement.src = "";
        imgElement.style.display = "none";
        previewText.style.display = "block";
      }

  </script>
  
  <!-- Modal untuk Preview Gambar -->
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
  </div>
</body>
</html>
