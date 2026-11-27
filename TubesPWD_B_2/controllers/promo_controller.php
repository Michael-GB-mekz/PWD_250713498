<?php
function promo_index() {
    $userId = 0;
    $promoSaya = array();
    if (sudah_login() && !adalah_staf()) {
        $userId = (int)$_SESSION['user_id'];
        $promoSaya = promos_milik_user($userId);
    }

    $data = array(
        'halaman'   => 'promo',
        'judul'     => 'Promo - Caritas Cafe',
        'definisi'  => promos_otomatis_aktif(),
        'bisaKlaim' => promos_bisa_diklaim($userId),
        'promoSaya' => $promoSaya
    );
    view('promo/index', $data);
}

function promo_klaim() {
    if (adalah_staf()) {
        flash('error', 'Promo hanya bisa diklaim oleh akun pelanggan.');
        redirect('promo');
    }
    $promoId = (int)post('promo_id');
    $hasil = promos_klaim((int)$_SESSION['user_id'], $promoId);

    if ($hasil == 'ok') {
        $promo = promos_cari($promoId);
        flash('sukses', "Promo '" . $promo['nama_promo'] . "' berhasil diklaim! Berlaku " . $promo['berlaku_hari'] . ' hari, pakai sebelum habis ya.');
    } elseif ($hasil == 'sudah') {
        flash('error', 'Kamu sudah mengklaim promo ini.');
    } elseif ($hasil == 'habis') {
        flash('error', 'Maaf, kuota promo ini sudah habis.');
    } elseif ($hasil == 'berakhir') {
        flash('error', 'Masa klaim promo ini sudah berakhir.');
    } else {
        flash('error', 'Promo tidak ditemukan.');
    }
    redirect('promo');
}
?>
