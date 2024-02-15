<?php
include 'koneksi.php';

// Pastikan ID disertakan dalam URL
if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    // Buat prepared statement untuk menghapus data
    $stmt = $conn->prepare("DELETE FROM laporan WHERE id = ?");
    $stmt->bind_param("i", $id_produk);

    // Eksekusi statement
    if ($stmt->execute()) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman produk
        header("Location: Index.php");
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan kesalahan
        echo "Error: " . $stmt->error;
    }

    // Tutup statement
    $stmt->close();
}

// Tutup koneksi
$conn->close();
?>
