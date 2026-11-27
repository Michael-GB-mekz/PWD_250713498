<?php
function cart_tambah() {
    $menu = menus_cari((int)post('menu_id'));
    if (!$menu) {
        flash('error', 'Menu tidak ditemukan.');
        redirect('menu');
    }

    $penyajian = post('penyajian');
    $harga = (int)$menu['harga_hot'];
    if ($penyajian == 'Ice') {
        $harga = (int)$menu['harga_ice'];
    }

    $qty = (int)post('qty', 1);
    if ($qty < 1) {
        $qty = 1;
    }

    $es = post('es');
    if ($penyajian != 'Ice') {
        $es = '';
    }

    keranjang_tambah($menu['id'], $menu['nama_menu'], $menu['grup_menu'], $penyajian, $harga, post('gula'), $es, post('catatan'), $qty);

    if (post('aksi') == 'beli') {
        redirect('home', array('open_cart' => 1, 'langkah' => 'bayar'));
    }
    flash('sukses', $qty . 'x ' . $menu['nama_menu'] . ' ditambahkan ke keranjang!');
    redirect('menu/detail', array('id' => $menu['id']));
}

function cart_tambah_qty() {
    keranjang_ubah_qty((int)$_GET['i'], 1);
    redirect('home', array('open_cart' => 1));
}

function cart_set_qty() {
    $qty = (int)(isset($_GET['qty']) ? $_GET['qty'] : 1);
    keranjang_set_qty((int)$_GET['i'], $qty);
    redirect('home', array('open_cart' => 1));
}

function cart_kurang_qty() {
    keranjang_ubah_qty((int)$_GET['i'], -1);
    redirect('home', array('open_cart' => 1));
}
?>
