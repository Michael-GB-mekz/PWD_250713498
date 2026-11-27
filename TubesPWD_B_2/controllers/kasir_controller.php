<?php
function kasir_daftar_metode_bayar() {
    return array('Tunai', 'QRIS', 'Kartu Debit');
}

function kasir_index() {
    $data = array(
        'halamanAdmin' => 'kasir',
        'judul'        => 'Kasir',
        'menuPerGrup'  => menus_per_grup(),
        'isiKeranjang' => keranjang_semua('pos'),
        'subtotal'     => keranjang_subtotal('pos'),
        'metodeBayar'  => kasir_daftar_metode_bayar(),
        'skripTambahan' => 'kasir.js'
    );
    view('kasir/index', $data, 'admin');
}

function kasir_tambah() {
    $menu = menus_cari((int)post('menu_id'));
    if (!$menu) {
        flash('error', 'Menu tidak ditemukan.');
        redirect('kasir');
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

    keranjang_tambah($menu['id'], $menu['nama_menu'], $menu['grup_menu'], $penyajian, $harga, post('gula'), $es, post('catatan'), $qty, 'pos');
    redirect('kasir');
}

function kasir_tambah_qty() {
    keranjang_ubah_qty((int)$_GET['i'], 1, 'pos');
    redirect('kasir');
}

function kasir_kurang_qty() {
    keranjang_ubah_qty((int)$_GET['i'], -1, 'pos');
    redirect('kasir');
}

function kasir_set_qty() {
    keranjang_set_qty((int)$_GET['i'], (int)(isset($_GET['qty']) ? $_GET['qty'] : 1), 'pos');
    redirect('kasir');
}

function kasir_kosongkan() {
    keranjang_kosongkan('pos');
    redirect('kasir');
}

function kasir_simpan() {
    global $dataLokasi;

    $keranjang = keranjang_semua('pos');
    $metode = post('metode_bayar');
    $lokasi = post('lokasi');

    if (count($keranjang) == 0) {
        flash('error', 'Keranjang kasir masih kosong.');
        redirect('kasir');
    } elseif (!in_array($metode, kasir_daftar_metode_bayar())) {
        flash('error', 'Pilih metode pembayaran.');
        redirect('kasir');
    } elseif (!isset($dataLokasi[$lokasi])) {
        flash('error', 'Pilih lokasi outlet.');
        redirect('kasir');
    }

    $uangDiterima = null;
    if ($metode == 'Tunai') {
        $uang = post('uang_diterima');
        if (!ctype_digit($uang) || (int)$uang < keranjang_subtotal('pos')) {
            flash('error', 'Uang diterima kurang dari total pembayaran.');
            redirect('kasir');
        }
        $uangDiterima = (int)$uang;
    }

    $nama = post('nama_pelanggan');
    if ($nama == '') {
        $nama = 'Pelanggan';
    }
    $teks = orders_susun_rincian($keranjang);
    $subtotal = keranjang_subtotal('pos');

    $data = array(
        'kode'         => orders_nomor_antrean(),
        'user_id'      => null,
        'nama'         => $nama,
        'no_wa'        => '',
        'lokasi'       => $lokasi,
        'jarak'        => 'Kasir',
        'metode_ambil' => post('tipe_pesanan'),
        'waktu_ambil'  => 'Sekarang',
        'metode_bayar' => $metode,
        'subtotal'     => $subtotal,
        'diskon'       => 0,
        'total'        => $subtotal,
        'uang'         => $uangDiterima,
        'rincian'      => $teks[0],
        'catatan'      => $teks[1],
        'promo_nama'   => null,
        'sumber'       => 'kasir',
        'dibuat_oleh'  => (int)$_SESSION['user_id']
    );
    $orderId = orders_buat($data, $keranjang);
    keranjang_kosongkan('pos');

    flash('sukses', 'Transaksi ' . $data['kode'] . ' berhasil disimpan.');
    redirect('kasir/struk', array('id' => $orderId));
}

function kasir_riwayat() {
    $hariIni = date('Y-m-d');
    $dari = isset($_GET['dari']) ? $_GET['dari'] : $hariIni;
    $sampai = isset($_GET['sampai']) ? $_GET['sampai'] : $hariIni;
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dari)) {
        $dari = $hariIni;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $sampai)) {
        $sampai = $hariIni;
    }

    $kasirId = (int)$_SESSION['user_id'];
    if (role_adalah('admin')) {
        $kasirId = 0;
    }
    $transaksi = orders_riwayat_kasir($kasirId, $dari, $sampai);

    $total = 0;
    foreach ($transaksi as $t) {
        $total += $t['total_bayar'];
    }

    $data = array(
        'halamanAdmin' => 'riwayat',
        'judul'        => 'Riwayat Transaksi',
        'transaksi'    => $transaksi,
        'dari'         => $dari,
        'sampai'       => $sampai,
        'total'        => $total
    );
    view('kasir/riwayat', $data, 'admin');
}

function kasir_struk() {
    $pesanan = orders_cari((int)(isset($_GET['id']) ? $_GET['id'] : 0));

    $boleh = $pesanan && $pesanan['sumber'] == 'kasir' && (role_adalah('admin') || $pesanan['dibuat_oleh'] == $_SESSION['user_id']);
    if (!$boleh) {
        flash('error', 'Struk tidak ditemukan.');
        redirect('kasir/riwayat');
    }

    $data = array(
        'halamanAdmin' => 'riwayat',
        'judul'        => 'Struk ' . $pesanan['kode_antrean'],
        'pesanan'      => $pesanan,
        'items'        => orders_items($pesanan['id'])
    );
    view('kasir/struk', $data, 'admin');
}
?>
