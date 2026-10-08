<?php
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_foto = isset($_POST['id_foto']) ? $_POST['id_foto'] : null; // Untuk update atau insert
    $id_kegiatan = $_POST['id_kegiatan'];
    $id_titik = $_POST['id_titik'];
    $titik_meter = $_POST['titik_meter'];
    $lajur = $_POST['lajur'];

    $tanggal = date("Ymd_His");
    $upload_dir = "../uploads/";

    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Ambil data lama jika update
    if ($id_foto) {
        $query = $conn->query("SELECT lampiran1, lampiran2, lampiran3 FROM data_swakelola WHERE id_foto = '$id_foto'");
        $data_lama = $query->fetch_assoc();
    } else {
        $data_lama = ["lampiran1" => "", "lampiran2" => "", "lampiran3" => ""];
    }

    // Fungsi untuk upload atau hapus gambar
        function uploadFile($inputIndex, $defaultValue, $hapusKey) {
            global $tanggal, $upload_dir;

            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $max_file_size = 2 * 1024 * 1024; // 2MB

            // Jika file ditandai untuk dihapus, kosongkan nilai dan hapus dari server
            if ($_POST[$hapusKey] ?? "0" === "1") {
                if (!empty($defaultValue) && file_exists("../" . $defaultValue)) {
                    unlink("../" . $defaultValue);
                }
                return null; // Kembalikan NULL agar dihapus di database
            }

            if (!empty($_FILES['lampiran']['name'][$inputIndex])) {
                $file_tmp = $_FILES['lampiran']['tmp_name'][$inputIndex];
                $file_size = $_FILES['lampiran']['size'][$inputIndex];
                $file_ext = strtolower(pathinfo($_FILES['lampiran']['name'][$inputIndex], PATHINFO_EXTENSION));

                if (!in_array($file_ext, $allowed_extensions)) {
                    die("Format file tidak didukung.");
                }

                if ($file_size > $max_file_size) {
                    die("Ukuran file terlalu besar. Maksimal 2MB.");
                }

                // Hapus file lama sebelum menyimpan yang baru
                if (!empty($defaultValue) && file_exists("../" . $defaultValue)) {
                    unlink("../" . $defaultValue);
                }

                $file_name = "foto" . ($inputIndex + 1) . "_" . $tanggal . "." . $file_ext;
                $file_path = $upload_dir . $file_name;

                if (move_uploaded_file($file_tmp, $file_path)) {
                    return "uploads/" . $file_name;
                } else {
                    die("Gagal mengunggah file.");
                }
            }

            return $defaultValue; // Jika tidak ada file baru, gunakan gambar lama
        }


    // Proses upload atau hapus gambar
    $lampiran1 = uploadFile(0, $data_lama['lampiran1'], "hapus_lampiran1");
    $lampiran2 = uploadFile(1, $data_lama['lampiran2'], "hapus_lampiran2");
    $lampiran3 = uploadFile(2, $data_lama['lampiran3'], "hapus_lampiran3");

    // --- HITUNG KODE SWAKELOLA ---

    // Ambil akronim dari tabel data_kegiatan
    $stmtKegiatan = $conn->prepare("SELECT akronim FROM data_kegiatan WHERE id_kegiatan = ?");
    $stmtKegiatan->bind_param("i", $id_kegiatan);
    $stmtKegiatan->execute();
    $resultKegiatan = $stmtKegiatan->get_result();
    $rowKegiatan = $resultKegiatan->fetch_assoc();
    $akronim = isset($rowKegiatan['akronim']) ? $rowKegiatan['akronim'] : '';

    // Ambil nama_titik dari tabel data_titik
    $stmtTitik = $conn->prepare("SELECT nama_titik FROM data_titik WHERE id_titik = ?");
    $stmtTitik->bind_param("i", $id_titik);
    $stmtTitik->execute();
    $resultTitik = $stmtTitik->get_result();
    $rowTitik = $resultTitik->fetch_assoc();
    $nama_titik = isset($rowTitik['nama_titik']) ? $rowTitik['nama_titik'] : '';

    // Ambil dua karakter terakhir dari nama_titik
    $dua_karakter = (strlen($nama_titik) >= 2) ? substr($nama_titik, -2) : '';

    // Ambil bulan dan tahun saat ini
    $tanggal = date('d');
    $bulan = date('m'); // format dua digit
    $tahun = date('Y');

    // Format kode swakelola: <akronim><bulan><tahun><dua karakter terakhir nama_titik>+<titik_meter><lajur>
    $kode_swakelola = $akronim . $tanggal . $bulan . $tahun . $dua_karakter . '+' . $titik_meter . $lajur;

    // --- SIMPAN DATA KE DATABASE ---
        if ($id_foto) {
            // Update data
            $stmt = $conn->prepare("UPDATE data_swakelola SET 
                id_kegiatan = ?, id_titik = ?, titik_meter = ?, lajur = ?, 
                lampiran1 = ?, lampiran2 = ?, lampiran3 = ?, kode_swakelola = ?
                WHERE id_foto = ?");

            // Jika lampiran NULL, gunakan "NULL" di query
            $lampiran1 = $lampiran1 ?: NULL;
            $lampiran2 = $lampiran2 ?: NULL;
            $lampiran3 = $lampiran3 ?: NULL;

            $stmt->bind_param("iissssssi", $id_kegiatan, $id_titik, $titik_meter, $lajur, $lampiran1, $lampiran2, $lampiran3, $kode_swakelola, $id_foto);
        } else {
            // Insert data baru
            $stmt = $conn->prepare("INSERT INTO data_swakelola 
                (id_kegiatan, id_titik, titik_meter, lajur, lampiran1, lampiran2, lampiran3, kode_swakelola) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->bind_param("iissssss", $id_kegiatan, $id_titik, $titik_meter, $lajur, $lampiran1, $lampiran2, $lampiran3, $kode_swakelola);
        }


    if ($stmt->execute()) {
        header("Location: ../index.php?folder=pages&file=inputSwakelola&status=success");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
