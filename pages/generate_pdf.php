<?php
require('../fpdf/fpdf.php'); 
require('../koneksi.php'); 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../dist/img/PUPR.jpg', 15, 10, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(190, 10, 'PHOTO DOKUMENTASI', 0, 1, 'C');
        $this->Cell(190, 6, 'PRESERVASI JALAN DAN JEMBATAN', 0, 1, 'C');
        $this->Cell(190, 6, 'RUAS JALAN BETUNG - BTS. KOTA PALEMBANG', 0, 1, 'C');
        $this->Cell(190, 6, 'PPK 1.5 PROVINSI SUMATERA SELATAN', 0, 1, 'C');
        $this->Cell(190, 6, 'TAHUN ANGGARAN 2024', 0, 1, 'C');
        $this->Ln(5);
    }

    function SectionTitle($title)
    {
        $this->SetFillColor(205, 220, 57);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 7, $title, 1, 1, 'C', true);
        $this->Ln(2);
    }

    function KMInfo($km)
    {
        $this->SetFillColor(209, 196, 233);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(190, 7, "KM: $km", 1, 1, 'C', true);
        $this->Ln(5);
    }

    function AddPhotoRow($image, $condition)
    {
        if (!file_exists($image) || empty($image)) {
            return;
        }

        $xStart = $this->GetX();
        $yStart = $this->GetY();
        $this->Image($image, $xStart, $yStart, 100);
        $this->SetXY($xStart + 110, $yStart + 10);
        $this->SetFillColor(240, 240, 240);
        $this->Cell(60, 10, "KONDISI: $condition", 1, 1, 'C', true);
        $this->Ln(50);
    }
}

// Bersihkan output buffer sebelum mengirim header
if (ob_get_length()) ob_clean();

// Header untuk menampilkan PDF langsung di browser
header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="preview.pdf"');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 12);
$pdf->SectionTitle('PEKERJAAN : CAMPURAN ASPAL PANAS (CAP)');
$pdf->KMInfo('63+240 R');

if ($id > 0) {
    if (!$conn) {
        die("Koneksi database gagal!");
    }

    $query = "SELECT lampiran1, lampiran2, lampiran3 FROM data_swakelola WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        die("Data tidak ditemukan!");
    }

    $row = $result->fetch_assoc();
    $pdf->AddPhotoRow('../dist/img/' . $row['lampiran1'], '0%');
    $pdf->AddPhotoRow('../dist/img/' . $row['lampiran2'], '50%');
    $pdf->AddPhotoRow('../dist/img/' . $row['lampiran3'], '100%');
}

$pdf->Output('I', 'preview.pdf');
exit();
?>
