<?php
function pengaturan_ambil($kunci, $bawaan = '') {
    $baris = db_satu('SELECT nilai FROM pengaturan WHERE kunci = ?', 's', array($kunci));
    if ($baris && $baris['nilai'] != '') {
        return $baris['nilai'];
    }
    return $bawaan;
}

function pengaturan_simpan($kunci, $nilai) {
    db_jalankan('INSERT INTO pengaturan (kunci, nilai) VALUES (?, ?) ON DUPLICATE KEY UPDATE nilai = ?', 'sss', array($kunci, $nilai, $nilai));
}

function pengaturan_hapus($kunci) {
    db_jalankan('DELETE FROM pengaturan WHERE kunci = ?', 's', array($kunci));
}
?>
