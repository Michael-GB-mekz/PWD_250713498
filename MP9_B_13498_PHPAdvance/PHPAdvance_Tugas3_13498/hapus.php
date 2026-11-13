<?php
    require 'koneksi.php';

    $id = $_GET['id'] ?? '';

    if (!is_string($id) || !ctype_digit($id) || (int) $id < 1) {
        echo '<p style="color: red;">ID tidak valid !!!</p>';
        echo '<a href="index.php">Kembali ke daftar buku</a>';
        exit;   
    }

    $stmt = mysqli_prepare($conn, 'DELETE FROM buku WHERE id = ?');

    if (!$stmt) {
        die('Gagal menyiapkan penghapusan: ' . mysqli_error($conn));
    }

    $id = (int) $id;
    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (!mysqli_stmt_execute($stmt)) {
        die('Gagal menghapus data: ' . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo '<p>Data buku berhasil dihapus.</p>';
    } else {
        echo '<p>Data buku tidak ditemukan atau sudah dihapus.</p>';
    }

    mysqli_stmt_close($stmt);
    echo '<a href="index.php">Kembali ke daftar buku</a>';
?>