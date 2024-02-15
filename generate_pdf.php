<?php
require_once 'vendor/autoload.php'; // Include Dompdf autoload.php
include 'koneksi.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get data based on ID
    $stmt = $conn->prepare("SELECT * FROM laporan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Get the value of 'nama_pengguna' from the fetched data
        $nama_pengguna = htmlspecialchars($row['nama_pengguna']);

        // Create Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Load HTML content
        $html = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                }
                .title {
                    font-weight: bold;
                    font-size: 20px;
                    text-align: center;
                    text-decoration: underline;
                    margin-bottom: 40px;
                    margin-top: 30px;
                }
                .content {
                    margin-top: 10px;
                }
                .content p {
                    margin-bottom: 5px;
                    line-height: 1.5;
                    margin-left: 30px;
                    margin-right: 30px;
                }
                .content strong {
                    display: inline-block;
                    width: 150px; /* Sesuaikan lebar sesuai kebutuhan */
                    font-weight: bold;
                }
                span {
                    padding-right: 10px;
                }
            </style>
        </head>
        <body>
            <div class="title">LAPORAN PENGADUAN</div>
            <div class="content">
                <p><strong>Nama Pengguna</strong> <span>:</span> ' . $nama_pengguna . '</p>
                <p><strong>ID Pelanggan</strong> <span>:</span> ' . htmlspecialchars($row['id_pelanggan']) . '</p>
                <p><strong>Jenis Pengaduan</strong> <span>:</span> ' . htmlspecialchars($row['jenis_pengaduan']) . '</p>
                <p><strong>Nomor HP</strong> <span>:</span> ' . htmlspecialchars($row['no_hp']) . '</p>
                <p><strong>Email</strong> <span>:</span> ' . htmlspecialchars($row['email']) . '</p>
                <p><strong>Alamat</strong> <span>:</span> ' . htmlspecialchars($row['alamat']) . '</p>
                <p><strong>Kordinat</strong> <span>:</span> ' . htmlspecialchars($row['kordinat']) . '</p>
                <p><strong>Keterangan</strong> <span>:</span> ' . htmlspecialchars($row['kordinat_pindah']) . '</p>
                <p><strong>Status</strong> <span>:</span> ' . htmlspecialchars($row['status']) . '</p>
            </div>
        </body>
        </html>';

        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Set the filename for the downloaded PDF
        $pdfFileName = 'laporan_' . str_replace(' ', '_', $nama_pengguna) . '.pdf';

        // Output the generated PDF directly to the browser
        $dompdf->stream($pdfFileName, array("Attachment" => false));

        // Close the database connection
        $stmt->close();
        $conn->close();
        exit; // Stop further execution
    } else {
        echo "Data not found.";
    }
} else {
    echo "ID not provided.";
}
?>
