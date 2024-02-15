<?php
include 'koneksi.php';

// Periksa apakah data POST telah diterima
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir
    $nama_pengguna = $_POST['nama_pengguna'];
    $id_pelanggan = $_POST['id_pelanggan'];
    $jenis_pengaduan = $_POST['jenis_pengaduan'];
    $no_hp = $_POST['no_hp'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $kordinat = $_POST['kordinat'];
    $kordinat_pindah = $_POST['kordinat_pindah'];
    $status = $_POST['status'];

    // Periksa apakah input "Kordinat Pindah" tidak diisi
    if (empty($kordinat_pindah)) {
        $kordinat_pindah = "-"; // Set nilai default menjadi tanda strip "-"
    }

    // Lakukan penyisipan data ke dalam database
    $sql = "INSERT INTO laporan (nama_pengguna, id_pelanggan, jenis_pengaduan, no_hp, email, alamat, kordinat, kordinat_pindah, status) 
            VALUES ('$nama_pengguna', '$id_pelanggan', '$jenis_pengaduan', '$no_hp', '$email', '$alamat', '$kordinat', '$kordinat_pindah', '$status')";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Data berhasil ditambahkan";
    } else {
        $response['success'] = false;
        $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }

    echo json_encode($response);

    // Tutup koneksi
    $conn->close();
}
?>
