<?php
$zip = new ZipArchive();
$testZip = 'test.zip';
if ($zip->open($testZip, ZipArchive::CREATE) === TRUE) {
    $zip->addFromString('test.txt', 'Ini file uji coba.');
    $zip->close();
    echo "ZIP berhasil dibuat!";
} else {
    echo "Gagal membuat ZIP.";
}
?>
