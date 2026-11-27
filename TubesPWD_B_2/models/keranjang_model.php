<?php
function keranjang_di_database($kunci) {
    return $kunci == 'cart' && sudah_login() && !adalah_staf();
}

function keranjang_semua($kunci = 'cart') {
    if (keranjang_di_database($kunci)) {
        $baris = db_semua(
            'SELECT c.id, c.menu_id, m.nama_menu AS nama, m.grup_menu AS grup, c.penyajian, c.gula, c.es, c.catatan, c.qty, ' .
            "COALESCE(CASE WHEN c.penyajian = 'Ice' THEN m.harga_ice ELSE m.harga_hot END, 0) AS harga " .
            'FROM cart_items c JOIN menus m ON m.id = c.menu_id WHERE c.user_id = ? ORDER BY c.id',
            'i',
            array((int)$_SESSION['user_id'])
        );
        $hasil = array();
        foreach ($baris as $b) {
            $hasil[$b['id']] = $b;
        }
        return $hasil;
    }

    if (!isset($_SESSION[$kunci])) {
        $_SESSION[$kunci] = array();
    }
    return $_SESSION[$kunci];
}

function keranjang_tambah($menuId, $nama, $grup, $penyajian, $harga, $gula, $es, $catatan, $qty, $kunci = 'cart') {
    if (keranjang_di_database($kunci)) {
        $ada = db_satu('SELECT id FROM cart_items WHERE user_id = ? AND menu_id = ? AND penyajian = ? AND gula = ? AND es = ? AND catatan = ? LIMIT 1', 'iissss', array((int)$_SESSION['user_id'], $menuId, $penyajian, $gula, $es, $catatan));
        if ($ada) {
            db_jalankan('UPDATE cart_items SET qty = qty + ? WHERE id = ?', 'ii', array($qty, $ada['id']));
        } else {
            db_jalankan('INSERT INTO cart_items (user_id, menu_id, penyajian, gula, es, catatan, qty) VALUES (?, ?, ?, ?, ?, ?, ?)', 'iissssi', array((int)$_SESSION['user_id'], $menuId, $penyajian, $gula, $es, $catatan, $qty));
        }
        return;
    }

    keranjang_semua($kunci);
    $_SESSION[$kunci][] = array(
        'menu_id'   => $menuId,
        'nama'      => $nama,
        'grup'      => $grup,
        'penyajian' => $penyajian,
        'harga'     => $harga,
        'gula'      => $gula,
        'es'        => $es,
        'catatan'   => $catatan,
        'qty'       => $qty
    );
}

function keranjang_ubah_qty($nomor, $selisih, $kunci = 'cart') {
    if (keranjang_di_database($kunci)) {
        $userId = (int)$_SESSION['user_id'];
        db_jalankan('UPDATE cart_items SET qty = qty + ? WHERE id = ? AND user_id = ?', 'iii', array($selisih, $nomor, $userId));
        db_jalankan('DELETE FROM cart_items WHERE id = ? AND user_id = ? AND qty <= 0', 'ii', array($nomor, $userId));
        return;
    }

    keranjang_semua($kunci);
    if (!isset($_SESSION[$kunci][$nomor])) {
        return;
    }
    $_SESSION[$kunci][$nomor]['qty'] += $selisih;
    if ($_SESSION[$kunci][$nomor]['qty'] <= 0) {
        array_splice($_SESSION[$kunci], $nomor, 1);
    }
}

function keranjang_set_qty($nomor, $qty, $kunci = 'cart') {
    if ($qty > 999) {
        $qty = 999;
    }
    if (keranjang_di_database($kunci)) {
        $userId = (int)$_SESSION['user_id'];
        db_jalankan('UPDATE cart_items SET qty = ? WHERE id = ? AND user_id = ?', 'iii', array($qty, $nomor, $userId));
        db_jalankan('DELETE FROM cart_items WHERE id = ? AND user_id = ? AND qty <= 0', 'ii', array($nomor, $userId));
        return;
    }

    keranjang_semua($kunci);
    if (!isset($_SESSION[$kunci][$nomor])) {
        return;
    }
    if ($qty <= 0) {
        array_splice($_SESSION[$kunci], $nomor, 1);
    } else {
        $_SESSION[$kunci][$nomor]['qty'] = $qty;
    }
}

function keranjang_jumlah($kunci = 'cart') {
    $total = 0;
    foreach (keranjang_semua($kunci) as $item) {
        $total += $item['qty'];
    }
    return $total;
}

function keranjang_subtotal($kunci = 'cart') {
    $total = 0;
    foreach (keranjang_semua($kunci) as $item) {
        $total += $item['harga'] * $item['qty'];
    }
    return $total;
}

function keranjang_diskon($subtotal, $persen) {
    return (int)round($subtotal * $persen / 100);
}

function keranjang_total_bayar($kunci = 'cart') {
    return keranjang_subtotal($kunci);
}

function keranjang_kosongkan($kunci = 'cart') {
    if (keranjang_di_database($kunci)) {
        db_jalankan('DELETE FROM cart_items WHERE user_id = ?', 'i', array((int)$_SESSION['user_id']));
        return;
    }
    $_SESSION[$kunci] = array();
}

function keranjang_gabung_tamu() {
    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        return;
    }
    $itemTamu = $_SESSION['cart'];
    $_SESSION['cart'] = array();
    foreach ($itemTamu as $item) {
        keranjang_tambah($item['menu_id'], $item['nama'], $item['grup'], $item['penyajian'], $item['harga'], $item['gula'], $item['es'], $item['catatan'], $item['qty']);
    }
}
?>
