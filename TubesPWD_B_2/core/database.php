<?php
function koneksi() {
    static $conn = null;
    if ($conn == null) {
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAMA);
        if (!$conn) {
            die('Koneksi database gagal: ' . mysqli_connect_error());
        }
        mysqli_query($conn, "SET time_zone = '+07:00'");
    }
    return $conn;
}

function db_siapkan($sql, $tipe, $param) {
    $stmt = mysqli_prepare(koneksi(), $sql);
    if ($tipe != '') {
        mysqli_stmt_bind_param($stmt, $tipe, ...$param);
    }
    mysqli_stmt_execute($stmt);
    return $stmt;
}

function db_semua($sql, $tipe = '', $param = array()) {
    $stmt = db_siapkan($sql, $tipe, $param);
    $hasil = mysqli_stmt_get_result($stmt);
    $baris = array();
    while ($b = mysqli_fetch_assoc($hasil)) {
        $baris[] = $b;
    }
    mysqli_stmt_close($stmt);
    return $baris;
}

function db_satu($sql, $tipe = '', $param = array()) {
    $semua = db_semua($sql, $tipe, $param);
    if (count($semua) > 0) {
        return $semua[0];
    }
    return null;
}

function db_jalankan($sql, $tipe = '', $param = array()) {
    $stmt = db_siapkan($sql, $tipe, $param);
    $jumlah = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    return $jumlah;
}

function db_id_terakhir() {
    return mysqli_insert_id(koneksi());
}
?>
