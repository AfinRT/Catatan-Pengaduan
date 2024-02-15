<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama_pengguna = $_POST['nama_pengguna'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $jenis_pengaduan = $_POST['jenis_pengaduan'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $kordinat = $_POST['kordinat'];
    $kordinat_pindah = $_POST['kordinat_pindah'];

    // Update data ke database
    $stmt = $conn->prepare("UPDATE laporan SET nama_pengguna=?, id_pelanggan=?, jenis_pengaduan=?, no_hp=?, email=?, alamat=?, kordinat=?, kordinat_pindah=? WHERE id=?");
    $stmt->bind_param("ssssssssi", $nama_pengguna, $id_pelanggan, $jenis_pengaduan, $no_hp, $email, $alamat, $kordinat, $kordinat_pindah, $id);

    if ($stmt->execute()) {
        $response = array('success' => true, 'message' => 'Data berhasil diperbarui.');
    } else {
        $response = array('success' => false, 'message' => 'Terjadi kesalahan saat memperbarui data: ' . $stmt->error);
    }

    $stmt->close();
} else {
    $response = array('success' => false, 'message' => 'Metode request tidak valid.');
}

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
