<?php
function promos_semua_admin() {
    return db_semua('SELECT p.*, (SELECT COUNT(*) FROM user_promos u WHERE u.promo_id = p.id) AS penerima, (SELECT COALESCE(SUM(u.jumlah_dipakai), 0) FROM user_promos u WHERE u.promo_id = p.id) AS terpakai FROM promos p ORDER BY p.id DESC');
}

function promos_cari($id) {
    return db_satu('SELECT * FROM promos WHERE id = ?', 'i', array($id));
}

function promos_tambah($nama, $deskripsi, $persen, $hari, $batas, $aktif, $jenis, $kuota, $klaimSampai) {
    db_jalankan('INSERT INTO promos (nama_promo, deskripsi, diskon_persen, berlaku_hari, batas_pakai, aktif, jenis, kuota, klaim_sampai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', 'ssiiiisis', array($nama, $deskripsi, $persen, $hari, $batas, $aktif, $jenis, $kuota, $klaimSampai));
}

function promos_update($id, $nama, $deskripsi, $persen, $hari, $batas, $aktif, $jenis, $kuota, $klaimSampai) {
    db_jalankan('UPDATE promos SET nama_promo = ?, deskripsi = ?, diskon_persen = ?, berlaku_hari = ?, batas_pakai = ?, aktif = ?, jenis = ?, kuota = ?, klaim_sampai = ? WHERE id = ?', 'ssiiiisisi', array($nama, $deskripsi, $persen, $hari, $batas, $aktif, $jenis, $kuota, $klaimSampai, $id));
}

function promos_hapus($id) {
    db_jalankan('DELETE FROM promos WHERE id = ?', 'i', array($id));
}

function promos_berikan_ke_semua($promoId) {
    return db_jalankan('INSERT IGNORE INTO user_promos (user_id, promo_id, berlaku_sampai) SELECT u.id, p.id, DATE_ADD(NOW(), INTERVAL p.berlaku_hari DAY) FROM users u JOIN promos p ON p.id = ?', 'i', array($promoId));
}

function promos_otomatis_aktif() {
    return db_semua("SELECT * FROM promos WHERE aktif = 1 AND jenis = 'otomatis' ORDER BY diskon_persen DESC");
}

function promos_berikan_ke_user($userId) {
    foreach (promos_otomatis_aktif() as $p) {
        db_jalankan('INSERT IGNORE INTO user_promos (user_id, promo_id, berlaku_sampai) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? DAY))', 'iii', array($userId, $p['id'], $p['berlaku_hari']));
    }
}

function promos_bisa_diklaim($userId) {
    return db_semua(
        'SELECT p.*, (SELECT COUNT(*) FROM user_promos u WHERE u.promo_id = p.id) AS terklaim, ' .
        '(SELECT COUNT(*) FROM user_promos u2 WHERE u2.promo_id = p.id AND u2.user_id = ?) AS sudah_klaim ' .
        "FROM promos p WHERE p.aktif = 1 AND p.jenis = 'klaim' AND (p.klaim_sampai IS NULL OR p.klaim_sampai > NOW()) " .
        'ORDER BY p.diskon_persen DESC, p.id DESC',
        'i',
        array($userId)
    );
}

function promos_jumlah_bisa_diklaim($userId) {
    $baris = db_satu(
        "SELECT COUNT(*) AS jumlah FROM promos p WHERE p.aktif = 1 AND p.jenis = 'klaim' AND (p.klaim_sampai IS NULL OR p.klaim_sampai > NOW()) " .
        'AND (p.kuota = 0 OR (SELECT COUNT(*) FROM user_promos u WHERE u.promo_id = p.id) < p.kuota) ' .
        'AND NOT EXISTS (SELECT 1 FROM user_promos u2 WHERE u2.promo_id = p.id AND u2.user_id = ?)',
        'i',
        array($userId)
    );
    return (int)$baris['jumlah'];
}

function promos_klaim($userId, $promoId) {
    $jumlah = db_jalankan(
        'INSERT IGNORE INTO user_promos (user_id, promo_id, berlaku_sampai) ' .
        'SELECT ?, p.id, DATE_ADD(NOW(), INTERVAL p.berlaku_hari DAY) FROM promos p ' .
        "WHERE p.id = ? AND p.aktif = 1 AND p.jenis = 'klaim' AND (p.klaim_sampai IS NULL OR p.klaim_sampai > NOW()) " .
        'AND (p.kuota = 0 OR (SELECT COUNT(*) FROM user_promos u WHERE u.promo_id = p.id) < p.kuota)',
        'ii',
        array($userId, $promoId)
    );
    if ($jumlah > 0) {
        return 'ok';
    }

    $promo = promos_cari($promoId);
    if (!$promo || !$promo['aktif'] || $promo['jenis'] != 'klaim') {
        return 'tidak_ada';
    }
    $sudah = db_satu('SELECT id FROM user_promos WHERE user_id = ? AND promo_id = ?', 'ii', array($userId, $promoId));
    if ($sudah) {
        return 'sudah';
    }
    if ($promo['klaim_sampai'] != null && strtotime($promo['klaim_sampai']) <= time()) {
        return 'berakhir';
    }
    return 'habis';
}

function promos_aktif_user($userId) {
    return db_satu(
        'SELECT up.id AS user_promo_id, up.berlaku_sampai, up.jumlah_dipakai, p.id AS promo_id, p.nama_promo, p.deskripsi, p.diskon_persen, p.batas_pakai ' .
        'FROM user_promos up JOIN promos p ON p.id = up.promo_id ' .
        'WHERE up.user_id = ? AND p.aktif = 1 AND up.berlaku_sampai > NOW() AND (p.batas_pakai = 0 OR up.jumlah_dipakai < p.batas_pakai) ' .
        'ORDER BY p.diskon_persen DESC LIMIT 1',
        'i',
        array($userId)
    );
}

function promos_aktif_semua_user($userId) {
    return db_semua(
        'SELECT up.id AS user_promo_id, up.berlaku_sampai, up.jumlah_dipakai, p.id AS promo_id, p.nama_promo, p.deskripsi, p.diskon_persen, p.batas_pakai ' .
        'FROM user_promos up JOIN promos p ON p.id = up.promo_id ' .
        'WHERE up.user_id = ? AND p.aktif = 1 AND up.berlaku_sampai > NOW() AND (p.batas_pakai = 0 OR up.jumlah_dipakai < p.batas_pakai) ' .
        'ORDER BY p.diskon_persen DESC',
        'i',
        array($userId)
    );
}

function promos_milik_user($userId) {
    return db_semua(
        'SELECT up.berlaku_sampai, up.jumlah_dipakai, p.nama_promo, p.deskripsi, p.diskon_persen, p.batas_pakai, ' .
        "CASE WHEN p.aktif = 0 THEN 'Dinonaktifkan' WHEN up.berlaku_sampai <= NOW() THEN 'Kedaluwarsa' " .
        "WHEN p.batas_pakai > 0 AND up.jumlah_dipakai >= p.batas_pakai THEN 'Sudah dipakai' ELSE 'Aktif' END AS status " .
        'FROM user_promos up JOIN promos p ON p.id = up.promo_id WHERE up.user_id = ? ORDER BY up.berlaku_sampai DESC',
        'i',
        array($userId)
    );
}

function promos_catat_pakai($userId, $promoId) {
    db_jalankan('UPDATE user_promos SET jumlah_dipakai = jumlah_dipakai + 1 WHERE user_id = ? AND promo_id = ?', 'ii', array($userId, $promoId));
}
?>
