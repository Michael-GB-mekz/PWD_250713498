<?php
function menu_index() {
    $signature = menus_signature();
    $mediaHero = hero_daftar_media();

    $data = array(
        'halaman'     => 'menu',
        'signature'   => $signature,
        'menuPerGrup' => menus_per_grup(),
        'mediaHero'   => $mediaHero,
        'detikHero'   => (int)pengaturan_ambil('hero_detik', '4'),
        'definisiPromo' => promos_otomatis_aktif(),
        'jumlahKlaim'   => sudah_login() ? promos_jumlah_bisa_diklaim((int)$_SESSION['user_id']) : 0
    );
    view('menu/index', $data);
}

function menu_lengkap() {
    view('menu/lengkap', array('halaman' => 'menu', 'judul' => 'Menu Lengkap - Caritas Cafe', 'menuPerGrup' => menus_per_grup()));
}

function menu_detail() {
    $id = 0;
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
    }
    $menu = menus_cari($id);
    if (!$menu) {
        flash('error', 'Menu tidak ditemukan.');
        redirect('menu');
    }
    $terjual = terjual_menu();
    $data = array(
        'halaman'     => 'menu',
        'judul'       => $menu['nama_menu'] . ' - Caritas Cafe',
        'menu'        => $menu,
        'terjual'     => isset($terjual[$menu['id']]) ? $terjual[$menu['id']] : 0,
        'rekomendasi' => menus_rekomendasi(!adalah_roti($menu['grup_menu']), $menu['id'], 3)
    );
    view('menu/detail', $data);
}

function menu_simpan_lokasi() {
    global $dataLokasi;

    $lokasi = post('lokasi_ambil');
    if (isset($dataLokasi[$lokasi])) {
        $_SESSION['lokasi_pilihan'] = $lokasi;
    }
    $_SESSION['jarak_info'] = post('jarak_info', 'Manual');
    redirect('home');
}
?>
