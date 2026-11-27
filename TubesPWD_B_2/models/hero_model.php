<?php
function hero_semua() {
    return db_semua('SELECT * FROM hero_fotos ORDER BY urutan ASC, id ASC');
}

function hero_cari($id) {
    return db_satu('SELECT * FROM hero_fotos WHERE id = ?', 'i', array($id));
}

function hero_tambah($sumber, $jenis) {
    $baris = db_satu('SELECT COALESCE(MAX(urutan), 0) AS terakhir FROM hero_fotos');
    db_jalankan('INSERT INTO hero_fotos (sumber, jenis, urutan) VALUES (?, ?, ?)', 'ssi', array($sumber, $jenis, (int)$baris['terakhir'] + 1));
}

function hero_hapus($id) {
    db_jalankan('DELETE FROM hero_fotos WHERE id = ?', 'i', array($id));
}

function hero_jenis_dari_nama($nama) {
    $jalur = parse_url($nama, PHP_URL_PATH);
    $ekstensi = strtolower(pathinfo($jalur, PATHINFO_EXTENSION));
    if ($ekstensi == 'jpg' || $ekstensi == 'jpeg' || $ekstensi == 'png' || $ekstensi == 'webp') {
        return 'foto';
    }
    if ($ekstensi == 'mp4' || $ekstensi == 'webm') {
        return 'video';
    }
    return '';
}

function hero_daftar_media() {
    $daftar = array();
    foreach (hero_semua() as $m) {
        $daftar[] = array('sumber' => $m['sumber'], 'jenis' => $m['jenis']);
    }
    if (count($daftar) > 0) {
        return $daftar;
    }

    $bawaan = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=900&q=80';
    $signature = menus_signature();
    if (count($signature) > 0 && !empty($signature[0]['gambar_url'])) {
        $bawaan = $signature[0]['gambar_url'];
    }
    return array(array('sumber' => $bawaan, 'jenis' => 'foto'));
}
?>
