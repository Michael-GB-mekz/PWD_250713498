<?php
function order_bayar() {
    if (!sudah_login()) {
        flash('error', 'Silakan masuk dulu untuk menyelesaikan pembayaran.');
        redirect('login');
    }

    if (adalah_staf()) {
        flash('error', 'Admin dan kasir tidak bisa memesan sebagai pelanggan. Gunakan menu Kasir.');
        redirect('home');
    }

    $akun = users_cari((int)$_SESSION['user_id']);
    $nama = $akun['nama_lengkap'];
    $noWa = $akun['no_hp'];
    $keranjang = keranjang_semua();

    if (count($keranjang) == 0) {
        flash('error', 'Keranjang masih kosong.');
        redirect('home');
    }

    $metodeBayar = post('metode_bayar');
    if (!in_array($metodeBayar, array('QRIS', 'Transfer Virtual Account', 'Bayar di Kasir'))) {
        flash('error', 'Pilih metode pembayaran.');
        redirect('home', array('open_cart' => 1, 'langkah' => 'bayar'));
    }

    if ($metodeBayar == 'Bayar di Kasir' && post('komitmen') != '1') {
        flash('error', 'Centang komitmen dulu untuk membayar di kasir.');
        redirect('home', array('open_cart' => 1, 'langkah' => 'bayar'));
    }

    $teks = orders_susun_rincian($keranjang);
    $subtotal = keranjang_subtotal();

    $promo = null;
    $diskon = 0;
    $namaPromo = null;
    $promoId = (int)post('promo_id');
    if ($promoId > 0) {
        foreach (promos_aktif_semua_user((int)$_SESSION['user_id']) as $p) {
            if ($p['promo_id'] == $promoId) {
                $promo = $p;
            }
        }
        if (!$promo) {
            flash('error', 'Promo yang dipilih tidak bisa dipakai.');
            redirect('home', array('open_cart' => 1, 'langkah' => 'bayar'));
        }
        $diskon = keranjang_diskon($subtotal, (int)$promo['diskon_persen']);
        $namaPromo = $promo['nama_promo'];
    }

    $data = array(
        'kode'         => orders_nomor_antrean(),
        'user_id'      => (int)$_SESSION['user_id'],
        'nama'         => $nama,
        'no_wa'        => $noWa,
        'lokasi'       => $_SESSION['lokasi_pilihan'],
        'jarak'        => $_SESSION['jarak_info'],
        'metode_ambil' => 'Pickup',
        'waktu_ambil'  => post('waktu_ambil'),
        'metode_bayar' => $metodeBayar,
        'subtotal'     => $subtotal,
        'diskon'       => $diskon,
        'total'        => $subtotal - $diskon,
        'rincian'      => $teks[0],
        'catatan'      => $teks[1],
        'promo_nama'   => $namaPromo,
        'sumber'       => 'online',
        'dibuat_oleh'  => null
    );
    orders_buat($data, $keranjang);
    if ($promo) {
        promos_catat_pakai((int)$_SESSION['user_id'], (int)$promo['promo_id']);
    }
    keranjang_kosongkan();

    flash('sukses', 'Pesanan berhasil dibuat!');
    redirect('profil/pesanan');
}

function order_struk() {
    $pesanan = orders_cari((int)(isset($_GET['id']) ? $_GET['id'] : 0));
    if (!$pesanan || $pesanan['user_id'] != $_SESSION['user_id']) {
        flash('error', 'Struk tidak ditemukan.');
        redirect('profil/pesanan');
    }

    $data = array(
        'halaman' => 'akun',
        'judul'   => 'Struk ' . $pesanan['kode_antrean'],
        'pesanan' => $pesanan,
        'items'   => orders_items($pesanan['id'])
    );
    view('order/struk', $data);
}

function order_ubah() {
    global $dataLokasi;

    $lokasi = post('lokasi_ambil');
    if (!isset($dataLokasi[$lokasi])) {
        $lokasi = 'Caritas - Kampus 3 UAJY';
    }
    orders_ubah_user((int)post('order_id'), (int)$_SESSION['user_id'], $lokasi, post('catatan'));

    flash('sukses', 'Pesanan berhasil diperbarui!');
    redirect('profil/pesanan');
}

function order_batal() {
    $orderId = (int)post('order_id');
    $pesanan = orders_cari($orderId);

    if ($pesanan && $pesanan['user_id'] == $_SESSION['user_id'] && $pesanan['status'] == 'Pesanan diterima') {
        orders_batal_user($orderId, (int)$_SESSION['user_id']);
        flash('sukses', 'Pesanan dibatalkan.');
    }
    redirect('profil/pesanan');
}
?>
