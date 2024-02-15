<?php
include 'koneksi.php';
include 'Navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengaduan</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://unpkg.com/html2pdf.js"></script>
    <script src="pdf.js"></script> 
    <link rel="stylesheet" href="CSS/laporan.css">
</head>

<body>
    <h2><i class="fas fa-exclamation"></i> Pengaduan Pelanggan</h2>

    <form id="filterForm" action="" method="get">
    <a href="#" class="btn0" onclick="togglePopup()">+ Pengaduan</a>
        <div class="filter">
            <label for="filter"><i class="fas fa-filter"></i>Filter </label><br>
            <select name="filter">
                <option value="">-- Pilih Status --</option>
                <option value="Belum Di Proses">Belum Di Proses</option>
                <option value="Selesai">Selesai</option>
            </select>
            <input type="submit" value="Filter">
        </div>
    </form>
    <?php
    // Fungsi untuk mendapatkan data dari database
    function getData($conn, $filter)
    {
        $sql = "SELECT * FROM laporan";

        // Tambahkan filter jika diberikan
        if (!empty($filter)) {
            $sql = "SELECT * FROM laporan WHERE status = ?";
        }

        $stmt = $conn->prepare($sql);

        if (!empty($filter)) {
            // Bind parameter untuk filter
            $stmt->bind_param("s", $filter);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>No</th><th>Nama Pengguna</th><th>ID Pelanggan</th><th>Jenis Pengaduan</th><th>Nomor HP</th><th>Kordinat</th><th>Status</th><th>Aksi</th></tr>";
                $no = 1; // Nomor urut
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>"; // Nomor urut
                    echo "<td>" . $row['nama_pengguna'] . "</td>";
                    echo "<td>" . $row['id_pelanggan'] . "</td>";
                    echo "<td>" . $row['jenis_pengaduan'] . "</td>";
                    echo "<td>" . $row['no_hp'] . "</td>";
                    echo "<td>" . $row['kordinat'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>
                    <a href='#' onclick='showDetailsPopup(" . $row['id'] . ")'><i class='fas fa-eye eye-icon'></i></a> |
                    <a href='#' onclick='toggleEditPopup(" . $row['id'] . ")'><i class='fas fa-edit edit-icon'></i></a> | 
                    <a href='#' onclick='deleteProduct(" . $row['id'] . ")'><i class='fas fa-trash delete-icon'></i></a> |
                    <a href='#' onclick='downloadPdf(" . $row['id'] . ", \"" . $row['nama_pengguna'] . "\")'><i class='fas fa-download download-icon'></i></a> |
                    <a href='#' onclick='updateProduct(" . $row['id'] . ")'><i class='fas fa-check check-icon'></i></a> 
                          </td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Tidak ada data yang ditemukan.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }

        // Tutup statement
        $stmt->close();
    }

    // Ambil data berdasarkan filter
    $filter = isset($_GET['filter']) ? $_GET['filter'] : '';
    getData($conn, $filter);

    // Tutup koneksi
    $conn->close();
    ?>
    <script>
        // Your existing JavaScript code...

        // Function to download PDF
        function downloadPdf(id, namaPengguna) {
            fetch('generate_pdf.php?id=' + id)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.blob();
                })
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;

                    // Set the filename based on the user's name
                    a.download = 'laporan_' + namaPengguna + '.pdf';

                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                })
                .catch(error => {
                    console.error('Error during fetch:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengunduh PDF. Silakan coba lagi.',
                        icon: 'error'
                    });
                });
        }
    </script>
    <script>
        // Fungsi untuk menampilkan SweetAlert konfirmasi penghapusan
        function deleteProduct(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus produk?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                customClass: {
                    popup: 'custom-popup-class1',
                    title: 'custom-title-class',
                    content: 'custom-content-class1',
                    confirmButton: 'custom-confirm-button-class1',
                    cancelButton: 'custom-cancel-button-class1'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'delete_laporan.php?id=' + id;
                }
            });
        }
    </script>
    <script>
        // Fungsi untuk menampilkan SweetAlert konfirmasi Update Status
        function updateProduct(id) {
            Swal.fire({
                title: 'Pesan Konfirmasi',
                text: 'Apakah Laporan Ini Sudah Selesai Di Proses?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                customClass: {
                    popup: 'custom-popup-class1',
                    title: 'custom-title-class',
                    content: 'custom-content-class1',
                    confirmButton: 'custom-confirm-button-class1',
                    cancelButton: 'custom-cancel-button-class1'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'update_status.php?id=' + id;
                }
            });
        }
    </script>
    <script>
// Fungsi untuk menampilkan popup formulir view produk
function showDetailsPopup(id) {
    fetch('get_produk_by_id.php?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const popupHtml = getPopupFormHtml1(data.data);
                Swal.fire({
                    title: 'Detail Pengaduan',
                    html: popupHtml,
                    showCancelButton: true,
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: '#d33',
                    showConfirmButton: false,
                    customClass: {
                        popup: 'custom-popup-class',
                        title: 'custom-title-class',
                        content: 'custom-content-class',
                        cancelButton: 'custom-cancel-button-class1'
                    },
                    didOpen: () => {
                        // Disable form fields when the modal is opened
                        disableFormFields();

                        // Add a custom button to download as PDF
                        const downloadBtn = document.createElement('button');
                        downloadBtn.id = 'downloadPdfBtn';
                        downloadBtn.className = 'swal2-confirm swal2-styled';
                        downloadBtn.textContent = 'Download to PDF';
                        downloadBtn.addEventListener('click', downloadToPdf);

                        Swal.getFooter().appendChild(downloadBtn);
                    },
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                    icon: 'error'
                });
            }
        })
        .catch((error) => {
            console.error('Error fetching data:', error);
            Swal.fire({
                title: 'Error',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                icon: 'error'
            });
        });
}
// Fungsi untuk mendapatkan HTML formulir popup view
function getPopupFormHtml1(data) {
    return `
        <label for="nama_pengguna" class="left-align-label">Nama Pengguna:</label>
        <input id="nama" type="text" class="swal2-input" value="${data.nama_pengguna}" required>

        <label for="id_pelanggan" class="left-align-label">ID Pengguna:</label>
        <input id="id" type="text" class="swal2-input" value="${data.id_pelanggan}" required>

        <label for="nama_produk" class="left-align-label">Jenis Pengaduan:</label>
        <select id="nama_produk" class="swal2-select" required>
            <option value="">--Pilih Disini--</option>
            <option value="UPGRADE SPEED" ${data.jenis_pengaduan === 'UPGRADE SPEED' ? 'selected' : ''}>UPGRADE SPEED</option>
            <option value="DOWNGRADE SPEED" ${data.jenis_pengaduan === 'DOWNGRADE SPEED' ? 'selected' : ''}>DOWNGRADE SPEED</option>
            <option value="MIGRASI PRODUK" ${data.jenis_pengaduan === 'MIGRASI PRODUK' ? 'selected' : ''}>MIGRASI PRODUK</option>
            <option value="PENCABUTAN" ${data.jenis_pengaduan === 'PENCABUTAN' ? 'selected' : ''}>PENCABUTAN</option>
            <option value="PINDAH ALAMAT" ${data.jenis_pengaduan === 'PINDAH ALAMAT' ? 'selected' : ''}>PINDAH ALAMAT</option>
        </select>

        <label for="no_hp" class="left-align-label">Nomor HP:</label>
        <input id="no_hp" type="text" class="swal2-input" value="${data.no_hp}" required>

        <label for="email" class="left-align-label">Email:</label>
        <input id="email" type="text" class="swal2-input" value="${data.email}" required>

        <label for="alamat" class="left-align-label">Alamat:</label>
        <textarea id="alamat" class="swal2-textarea" required>${data.alamat}</textarea>

        <label for="kordinat" class="left-align-label">Kordinat:</label>
        <input id="kordinat" type="text" class="swal2-input" value="${data.kordinat}" required>

        <label for="kordinat_pindah" class="left-align-label">Keterangan:</label>
        <textarea id="kordinat_pindah" class="swal2-textarea" required>${data.kordinat_pindah}</textarea>
    `;
}

// Fungsi untuk menonaktifkan kolom formulir
function disableFormFields() {
    const formFields = document.querySelectorAll('.swal2-input, .swal2-select, .swal2-textarea');
    formFields.forEach(field => {
        field.disabled = true;
    });
}

</script>
<script>

         // Function to toggle the edit popup
         function toggleEditPopup(id) {
            fetch('get_produk_by_id.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Edit Pengaduan',
                            html: getEditFormHtml(data.data),
                            showCancelButton: true,
                            confirmButtonText: 'Simpan',
                            cancelButtonText: 'Batal',
                            showLoaderOnConfirm: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            customClass: {
                                popup: 'custom-popup-class',
                                title: 'custom-title-class',
                                content: 'custom-content-class',
                                confirmButton: 'custom-confirm-button-class',
                                cancelButton: 'custom-cancel-button-class'
                            },
                            preConfirm: handleEditFormSubmission(id),
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Sukses',
                                    text: 'Pengaduan berhasil diperbarui!',
                                    icon: 'success'
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        }).catch((error) => {
                            Swal.fire({
                                title: 'Error',
                                text: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
                                icon: 'error'
                            });
                        });
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Terjadi kesalahan. Silakan coba lagi.',
                            icon: 'error'
                        });
                    }
                })
                .catch((error) => {
                    console.error('Error fetching data:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                        icon: 'error'
                    });
                });
        }

        // Function to get HTML for the edit form popup
        function getEditFormHtml(data) {
            return `
            <label for="edit_nama_pengguna" class="left-align-label">Nama Pengguna:</label>
            <input id="edit_nama_pengguna" type="text" class="swal2-input" value="${data.nama_pengguna}" required>

            <label for="edit_id_pelanggan" class="left-align-label">ID Pengguna:</label>
            <input id="edit_id_pelanggan" type="text" class="swal2-input" value="${data.id_pelanggan}" required>

            <label for="edit_jenis_pengaduan" class="left-align-label">Jenis Pengaduan:</label>
            <select id="edit_jenis_pengaduan" class="swal2-select" required>
                <option value="">--Pilih Disini--</option>
                <option value="UPGRADE SPEED" ${data.jenis_pengaduan === 'UPGRADE SPEED' ? 'selected' : ''}>UPGRADE SPEED</option>
                <option value="DOWNGRADE SPEED" ${data.jenis_pengaduan === 'DOWNGRADE SPEED' ? 'selected' : ''}>DOWNGRADE SPEED</option>
                <option value="MIGRASI PRODUK" ${data.jenis_pengaduan === 'MIGRASI PRODUK' ? 'selected' : ''}>MIGRASI PRODUK</option>
                <option value="PENCABUTAN" ${data.jenis_pengaduan === 'PENCABUTAN' ? 'selected' : ''}>PENCABUTAN</option>
                <option value="PINDAH ALAMAT" ${data.jenis_pengaduan === 'PINDAH ALAMAT' ? 'selected' : ''}>PINDAH ALAMAT</option>
            </select>

            <label for="edit_no_hp" class="left-align-label">Nomor HP:</label>
            <input id="edit_no_hp" type="text" class="swal2-input" value="${data.no_hp}" required>

            <label for="edit_email" class="left-align-label">Email:</label>
            <input id="edit_email" type="text" class="swal2-input" value="${data.email}" required>

            <label for="edit_alamat" class="left-align-label">Alamat:</label>
            <textarea id="edit_alamat" class="swal2-textarea" required>${data.alamat}</textarea>

            <label for="edit_kordinat" class="left-align-label">Kordinat:</label>
            <input id="edit_kordinat" type="text" class="swal2-input" value="${data.kordinat}" required>

            <label for="edit_kordinat_pindah" class="left-align-label">Keterangan:</label>
            <textarea id="edit_kordinat_pindah" class="swal2-textarea" required>${data.kordinat_pindah}</textarea>
            `;
        }

        // Function to handle the edit form submission
        function handleEditFormSubmission(id) {
            return () => {
                const edit_nama_pengguna = Swal.getPopup().querySelector('#edit_nama_pengguna').value;
                const edit_id_pelanggan = Swal.getPopup().querySelector('#edit_id_pelanggan').value;
                const edit_jenis_pengaduan = Swal.getPopup().querySelector('#edit_jenis_pengaduan').value;
                const edit_no_hp = Swal.getPopup().querySelector('#edit_no_hp').value;
                const edit_email = Swal.getPopup().querySelector('#edit_email').value;
                const edit_alamat = Swal.getPopup().querySelector('#edit_alamat').value;
                const edit_kordinat = Swal.getPopup().querySelector('#edit_kordinat').value;
                const edit_kordinat_pindah = Swal.getPopup().querySelector('#edit_kordinat_pindah').value;

                return fetch('proses_edit_laporan.php?id=' + id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'id': id,
                            'nama_pengguna': edit_nama_pengguna,
                            'id_pelanggan': edit_id_pelanggan,
                            'jenis_pengaduan': edit_jenis_pengaduan,
                            'no_hp': edit_no_hp,
                            'email': edit_email,
                            'alamat': edit_alamat,
                            'kordinat': edit_kordinat,
                            'kordinat_pindah': edit_kordinat_pindah,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            throw new Error(data.message || 'Terjadi kesalahan.');
                        }
                    });
            };
        }

        // Fungsi untuk menangani pengiriman formulir input
        function handleAddFormSubmission() {
            return () => {
                const add_nama_pengguna = Swal.getPopup().querySelector('#add_nama_pengguna').value;
                const add_id_pelanggan = Swal.getPopup().querySelector('#add_id_pelanggan').value;
                const add_jenis_pengaduan = Swal.getPopup().querySelector('#add_jenis_pengaduan').value;
                const add_no_hp = Swal.getPopup().querySelector('#add_no_hp').value;
                const add_email = Swal.getPopup().querySelector('#add_email').value;
                const add_alamat = Swal.getPopup().querySelector('#add_alamat').value;
                const add_kordinat = Swal.getPopup().querySelector('#add_kordinat').value;
                const add_kordinat_pindah = Swal.getPopup().querySelector('#add_kordinat_pindah').value;
                const add_status = Swal.getPopup().querySelector('#add_status').value;

                return fetch('proses_tambah_laporan.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            'nama_pengguna': add_nama_pengguna,
                            'id_pelanggan': add_id_pelanggan,
                            'jenis_pengaduan': add_jenis_pengaduan,
                            'no_hp': add_no_hp,
                            'email': add_email,
                            'alamat': add_alamat,
                            'kordinat': add_kordinat,
                            'kordinat_pindah': add_kordinat_pindah,
                            'status': add_status
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Sukses',
                                text: 'Produk berhasil ditambahkan!',
                                icon: 'success'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch((error) => {
                        Swal.fire({
                            title: 'Error',
                            text: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
                            icon: 'error'
                        });
                    });
            };
        }
    </script>
    <script>
        // Fungsi untuk menampilkan popup formulir input produk
        function togglePopup() {
            Swal.fire({
                title: 'Tambah Produk',
                html: getPopupFormHtml(),
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                showLoaderOnConfirm: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                customClass: {
                    popup: 'custom-popup-class',
                    title: 'custom-title-class',
                    content: 'custom-content-class',
                    confirmButton: 'custom-confirm-button-class',
                    cancelButton: 'custom-cancel-button-class'
                },
                preConfirm: handleFormSubmission,
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Sukses',
                        text: 'Produk berhasil ditambahkan ke database!',
                        icon: 'success'
                    }).then(() => {
                        location.reload();
                    });
                }
            }).catch((error) => {
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Terjadi kesalahan. Silakan coba lagi.',
                    icon: 'error'
                });
            });
        }

        // Fungsi untuk mendapatkan HTML formulir popup
        function getPopupFormHtml() {
            return `
            <label for="add_nama_pengguna" class="left-align-label">Nama Pengguna:</label>
<input id="add_nama_pengguna" type="text" class="swal2-input" required>

<label for="add_id_pelanggan" class="left-align-label">ID Pelanggan:</label>
<input id="add_id_pelanggan" type="text" class="swal2-input" required>

<label for="add_jenis_pengaduan" class="left-align-label">Jenis Pengaduan:</label>
<select id="add_jenis_pengaduan" class="swal2-select" required>
    <option value="">--Pilih Disini--</option>
    <option value="UPGRADE SPEED">UPGRADE SPEED</option>
    <option value="DOWNGRADE SPEED">DOWNGRADE SPEED</option>
    <option value="MIGRASI PRODUK">MIGRASI PRODUK</option>
    <option value="PENCABUTAN">PENCABUTAN</option>
    <option value="PINDAH ALAMAT">PINDAH ALAMAT</option>
</select>

<label for="add_no_hp" class="left-align-label">Nomor HP:</label>
<input id="add_no_hp" type="text" class="swal2-input" required>

<label for="add_email" class="left-align-label">Email:</label>
<input id="add_email" type="text" class="swal2-input" required>

<label for="add_alamat" class="left-align-label">Alamat:</label>
<textarea id="add_alamat" class="swal2-textarea" required></textarea>

<label for="add_kordinat" class="left-align-label">Kordinat:</label>
<input id="add_kordinat" type="text" class="swal2-input" required>

<label for="add_kordinat_pindah" class="left-align-label">Keterangan:</label>
<textarea id="add_kordinat_pindah" class="swal2-textarea" required></textarea>

<input type="hidden" name="add_status" id="add_status" value="Belum Di Proses">

        `;
    }

        // Fungsi untuk menangani pengiriman formulir
        function handleFormSubmission() {
            // Handle form submission here
            const add_nama_pengguna = Swal.getPopup().querySelector('#add_nama_pengguna').value;
            const add_id_pelanggan = Swal.getPopup().querySelector('#add_id_pelanggan').value;
            const add_jenis_pengaduan = Swal.getPopup().querySelector('#add_jenis_pengaduan').value;
            const add_no_hp = Swal.getPopup().querySelector('#add_no_hp').value;
            const add_email = Swal.getPopup().querySelector('#add_email').value;
            const add_alamat = Swal.getPopup().querySelector('#add_alamat').value;
            const add_kordinat = Swal.getPopup().querySelector('#add_kordinat').value;
            const add_kordinat_pindah = Swal.getPopup().querySelector('#add_kordinat_pindah').value;
            const add_status = Swal.getPopup().querySelector('#add_status').value;

            if (!add_nama_pengguna || !add_id_pelanggan || !add_jenis_pengaduan || !add_no_hp || !add_email || !add_alamat || !add_kordinat) {
                Swal.showValidationMessage('Lengkapi Data Anda!');
                return;
            }

            return fetch('proses_tambah_laporan.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'nama_pengguna': add_nama_pengguna,
                        'id_pelanggan': add_id_pelanggan,
                        'jenis_pengaduan': add_jenis_pengaduan,
                        'no_hp': add_no_hp,
                        'email': add_email,
                        'alamat': add_alamat,
                        'kordinat': add_kordinat,
                        'kordinat_pindah': add_kordinat_pindah,
                        'status': add_status
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Terjadi kesalahan.');
                    }
                });
        }
    </script>
</body>

</html>
