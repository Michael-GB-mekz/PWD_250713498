<?php
function menus_daftar_grup() {
    return array('FLAVOUR COFFEE', 'BASIC COFFEE', 'SIGNATURE COFFEE', 'TEA BASED', 'POWDER BASED', 'REFRESHMENTS', 'CROISSANT', 'SOURDOUGH', 'JOGJA BUN');
}

function menus_daftar_tag() {
    return array('Flavour', 'Basic', 'Signature', 'Tea', 'Powder', 'Pastry');
}

function menus_cari($id) {
    return db_satu('SELECT * FROM menus WHERE id = ?', 'i', array($id));
}

function menus_semua() {
    return db_semua('SELECT * FROM menus ORDER BY id DESC');
}

function menus_jumlah() {
    $baris = db_satu('SELECT COUNT(*) AS jumlah FROM menus');
    return (int)$baris['jumlah'];
}

function menus_signature() {
    return db_semua('SELECT * FROM menus WHERE is_signature = 1 ORDER BY id ASC');
}

function menus_per_grup() {
    $semua = db_semua("SELECT * FROM menus ORDER BY FIELD(grup_menu, 'FLAVOUR COFFEE','BASIC COFFEE','SIGNATURE COFFEE','TEA BASED','POWDER BASED','REFRESHMENTS','CROISSANT','SOURDOUGH','JOGJA BUN'), id ASC");
    $perGrup = array();
    foreach ($semua as $menu) {
        $perGrup[$menu['grup_menu']][] = $menu;
    }
    return $perGrup;
}

function menus_rekomendasi($untukRoti, $kecualiId, $jumlah) {
    $roti = "('CROISSANT', 'SOURDOUGH', 'JOGJA BUN')";
    $kondisi = 'grup_menu IN ' . $roti;
    if (!$untukRoti) {
        $kondisi = 'grup_menu NOT IN ' . $roti;
    }
    return db_semua('SELECT * FROM menus WHERE ' . $kondisi . ' AND id <> ? AND COALESCE(harga_hot, harga_ice, 0) > 0 ORDER BY COALESCE(harga_hot, harga_ice) ASC, id ASC LIMIT ' . (int)$jumlah, 'i', array($kecualiId));
}

function menus_tambah($nama, $grup, $tag, $signature, $hargaHot, $hargaIce, $gambar, $deskripsi) {
    db_jalankan('INSERT INTO menus (nama_menu, grup_menu, filter_tag, is_signature, harga_hot, harga_ice, gambar_url, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)', 'sssiiiss', array($nama, $grup, $tag, $signature, $hargaHot, $hargaIce, $gambar, $deskripsi));
}

function menus_update_harga($id, $hargaHot, $hargaIce) {
    db_jalankan('UPDATE menus SET harga_hot = ?, harga_ice = ? WHERE id = ?', 'iii', array($hargaHot, $hargaIce, $id));
}

function menus_hapus($id) {
    db_jalankan('DELETE FROM menus WHERE id = ?', 'i', array($id));
}
?>
