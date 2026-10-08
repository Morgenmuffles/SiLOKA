<?php
// Dummy data kegiatan
$kegiatan = [
    [
        'id' => 1,
        'nama' => 'Pemeliharaan Jalan Lintas Timur',
        'lokasi' => 'Palembang – Betung',
        'status' => 'Selesai',
    ],
    [
        'id' => 2,
        'nama' => 'Pembuatan Drainase',
        'lokasi' => 'Jakabaring',
        'status' => 'Berlangsung',
    ],
    [
        'id' => 3,
        'nama' => 'Pengecatan Marka Jalan',
        'lokasi' => 'Ogan Ilir',
        'status' => 'Belum Mulai',
    ],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Dokumentasi Swakelola</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="card container mt-5 p-4">
    <h2 class="mb-4">📸 Dashboard Dokumentasi Kegiatan Swakelola</h2>

    <div class="table-responsive">
        <table class="table table-bordered table-striped bg-white">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Dokumentasi</th>
                    <th>Ekspor PDF</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kegiatan as $index => $k): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $k['nama'] ?></td>
                    <td><?= $k['lokasi'] ?></td>
                    <td><?= $k['status'] ?></td>
                    <td>
                        <div class="d-grid gap-2">
                            <div class="d-flex gap-2">
                                <a href="foto.php?id=<?= $k['id'] ?>&progress=0" class="btn btn-sm btn-secondary flex-fill">0%</a>
                                <a href="foto.php?id=<?= $k['id'] ?>&progress=50" class="btn btn-sm btn-warning flex-fill">50%</a>
                            </div>
                            <a href="foto.php?id=<?= $k['id'] ?>&progress=100" class="btn btn-sm btn-success w-100">100%</a>
                        </div>
                    </td>
                    <td>
                        <a href="export_pdf.php?id=<?= $k['id'] ?>" class="btn btn-sm btn-primary w-100">Ekspor PDF</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
