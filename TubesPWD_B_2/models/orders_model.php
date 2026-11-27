<?php
function orders_daftar_status() {
    return array('Pesanan diterima', 'Sedang dibuat barista', 'Siap diambil', 'Selesai');
}

function orders_nomor_antrean() {
    $baris = db_satu('SELECT COALESCE(MAX(id), 0) AS terakhir FROM orders');
    $nomor = (int)$baris['terakhir'] + 1;
    return 'A-' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
}

function orders_buat($d, $daftarItem) {
    db_jalankan(
        'INSERT INTO orders (kode_antrean, user_id, nama_pemesan, no_wa, lokasi_ambil, jarak_info, metode_ambil, waktu_ambil, metode_bayar, subtotal, diskon, total_bayar, uang_diterima, rincian_item, catatan, promo_nama, sumber, dibuat_oleh) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        'sisssssssiiiissssi',
        array($d['kode'], $d['user_id'], $d['nama'], $d['no_wa'], $d['lokasi'], $d['jarak'], $d['metode_ambil'], $d['waktu_ambil'], $d['metode_bayar'], $d['subtotal'], $d['diskon'], $d['total'], isset($d['uang']) ? $d['uang'] : null, $d['rincian'], $d['catatan'], $d['promo_nama'], $d['sumber'], $d['dibuat_oleh'])
    );
    $orderId = db_id_terakhir();

    foreach ($daftarItem as $item) {
        db_jalankan(
            'INSERT INTO order_items (order_id, menu_id, nama_menu, penyajian, qty, harga, catatan) VALUES (?, ?, ?, ?, ?, ?, ?)',
            'iissiis',
            array($orderId, $item['menu_id'], $item['nama'], $item['penyajian'], $item['qty'], $item['harga'], $item['catatan'])
        );
    }
    return $orderId;
}

function orders_susun_rincian($daftarItem) {
    $rincian = array();
    $catatan = array();
    foreach ($daftarItem as $item) {
        $rincian[] = $item['qty'] . 'x ' . $item['nama'] . ' (' . $item['penyajian'] . ') - ' . rupiah($item['harga'] * $item['qty']);
        if ($item['catatan'] != '') {
            $catatan[] = $item['nama'] . ': ' . $item['catatan'];
        }
    }
    return array(implode(' | ', $rincian), implode('; ', $catatan));
}

function orders_cari($id) {
    return db_satu('SELECT * FROM orders WHERE id = ?', 'i', array($id));
}

function orders_items($orderId) {
    return db_semua('SELECT * FROM order_items WHERE order_id = ? ORDER BY id', 'i', array($orderId));
}

function orders_milik_user($userId) {
    return db_semua('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC', 'i', array($userId));
}

function orders_ubah_user($id, $userId, $lokasi, $catatan) {
    db_jalankan("UPDATE orders SET lokasi_ambil = ?, catatan = ? WHERE id = ? AND user_id = ? AND status = 'Pesanan diterima'", 'ssii', array($lokasi, $catatan, $id, $userId));
}

function orders_batal_user($id, $userId) {
    return db_jalankan("DELETE FROM orders WHERE id = ? AND user_id = ? AND status = 'Pesanan diterima'", 'ii', array($id, $userId));
}

function orders_semua() {
    return db_semua('SELECT * FROM orders ORDER BY id DESC');
}

function orders_terbaru($batas) {
    return db_semua('SELECT * FROM orders ORDER BY id DESC LIMIT ' . (int)$batas);
}

function orders_ubah_status($id, $status) {
    db_jalankan('UPDATE orders SET status = ? WHERE id = ?', 'si', array($status, $id));
}

function orders_hapus($id) {
    db_jalankan('DELETE FROM orders WHERE id = ?', 'i', array($id));
}

function orders_ringkasan_hari_ini() {
    return db_satu('SELECT COUNT(*) AS jumlah, COALESCE(SUM(total_bayar), 0) AS omzet FROM orders WHERE DATE(created_at) = CURDATE()');
}

function orders_jumlah_aktif() {
    $baris = db_satu("SELECT COUNT(*) AS jumlah FROM orders WHERE status <> 'Selesai'");
    return (int)$baris['jumlah'];
}

function orders_riwayat_kasir($kasirId, $dari, $sampai) {
    $sql = "SELECT o.* FROM orders o WHERE o.sumber = 'kasir' AND DATE(o.created_at) BETWEEN ? AND ?";
    $tipe = 'ss';
    $param = array($dari, $sampai);
    if ($kasirId != 0) {
        $sql .= ' AND o.dibuat_oleh = ?';
        $tipe .= 'i';
        $param[] = $kasirId;
    }
    $sql .= ' ORDER BY o.id DESC';
    return db_semua($sql, $tipe, $param);
}

function orders_terjual_per_menu() {
    $peta = array();
    foreach (db_semua('SELECT menu_id, SUM(qty) AS terjual FROM order_items WHERE menu_id IS NOT NULL GROUP BY menu_id ORDER BY terjual DESC') as $r) {
        $peta[(int)$r['menu_id']] = (int)$r['terjual'];
    }
    return $peta;
}

?>
