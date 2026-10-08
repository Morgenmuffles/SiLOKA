<?php
require 'koneksi.php'; // Pastikan koneksi ke database sudah ada

if (isset($_GET['id'])) {
    $id_foto = intval($_GET['id']);

    // Ambil data foto dari database
    $query = $conn->prepare("SELECT lampiran1, lampiran2, lampiran3 FROM data_swakelola WHERE id_foto = ?");
    $query->bind_param("i", $id_foto);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Buat file ZIP sementara
        $zipFilePath = tempnam(sys_get_temp_dir(), "zip_");
        $zip = new ZipArchive();

        if ($zip->open($zipFilePath, ZipArchive::CREATE) === TRUE) {
            $fileAdded = false;

            // Tambahkan file ke dalam ZIP
            foreach (['lampiran1', 'lampiran2', 'lampiran3'] as $foto) {
                if (!empty($row[$foto]) && file_exists($row[$foto])) {
                    $zip->addFile($row[$foto], basename($row[$foto]));
                    $fileAdded = true;
                }
            }
            $zip->close();

            // Cek apakah ada file dalam ZIP
            if (!$fileAdded) {
                unlink($zipFilePath); // Hapus file kosong
                die("<script>alert('Gagal membuat ZIP! Tidak ada file yang ditambahkan.');</script>");
            }

            // Kirim ZIP ke browser
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="swakelola_' . $id_foto . '.zip"');
            header('Content-Length: ' . filesize($zipFilePath));

            ob_clean();
            flush();
            readfile($zipFilePath);

            // Hapus file ZIP setelah dikirim
            unlink($zipFilePath);
            exit();
        } else {
            unlink($zipFilePath);
            die("<script>alert('Gagal membuat ZIP!');</script>");
        }
    } else {
        die("<script>alert('Data tidak ditemukan!');</script>");
    }
}
?>
