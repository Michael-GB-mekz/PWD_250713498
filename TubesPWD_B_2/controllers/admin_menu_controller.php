<?php
function admin_menu_ambil_harga($nama) {
    $teks = post($nama);
    if ($teks == '') {
        return null;
    }
    return (int)$teks;
}

function admin_menu_index() {
    $data = array(
        'halamanAdmin' => 'menu',
        'judul'        => 'Kelola Menu',
        'daftarMenu'   => menus_semua()
    );
    view('admin/menu/index', $data, 'admin');
}

function admin_menu_tampil_form($lama) {
    $data = array(
        'halamanAdmin' => 'menu',
        'judul'        => 'Tambah Menu',
        'daftarGrup'   => menus_daftar_grup(),
        'daftarTag'    => menus_daftar_tag(),
        'lama'         => $lama
    );
    view('admin/menu/tambah', $data, 'admin');
}

function admin_menu_form_tambah() {
    admin_menu_tampil_form(array('nama_menu' => '', 'grup_menu' => '', 'filter_tag' => '', 'harga_hot' => '', 'harga_ice' => '', 'deskripsi' => '', 'gambar_url' => '', 'is_signature' => 0));
}

function admin_menu_tambah() {
    $lama = array(
        'nama_menu'    => post('nama_menu'),
        'grup_menu'    => post('grup_menu'),
        'filter_tag'   => post('filter_tag'),
        'harga_hot'    => post('harga_hot'),
        'harga_ice'    => post('harga_ice'),
        'deskripsi'    => post('deskripsi'),
        'gambar_url'   => post('gambar_url'),
        'is_signature' => isset($_POST['is_signature']) ? 1 : 0
    );

    $error = '';
    if ($lama['nama_menu'] == '') {
        $error = 'Nama menu wajib diisi.';
    } elseif (!in_array($lama['grup_menu'], menus_daftar_grup()) || !in_array($lama['filter_tag'], menus_daftar_tag())) {
        $error = 'Pilih grup dan tag menu.';
    } elseif (($lama['harga_hot'] != '' && !ctype_digit($lama['harga_hot'])) || ($lama['harga_ice'] != '' && !ctype_digit($lama['harga_ice']))) {
        $error = 'Harga harus berupa angka tanpa titik atau koma.';
    } elseif ($lama['harga_hot'] == '' && $lama['harga_ice'] == '') {
        $error = 'Isi minimal satu harga (Hot atau Ice).';
    } elseif ($lama['gambar_url'] != '' && !preg_match('/^https?:\/\/\S+$/', $lama['gambar_url'])) {
        $error = 'Alamat foto harus diawali http:// atau https://.';
    }

    if ($error != '') {
        flash('error', $error);
        admin_menu_tampil_form($lama);
        return;
    }

    $gambar = $lama['gambar_url'];
    if ($gambar == '') {
        $gambar = 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?auto=format&fit=crop&w=600&q=80';
    }
    $hargaHot = null;
    if ($lama['harga_hot'] != '') {
        $hargaHot = (int)$lama['harga_hot'];
    }
    $hargaIce = null;
    if ($lama['harga_ice'] != '') {
        $hargaIce = (int)$lama['harga_ice'];
    }
    menus_tambah($lama['nama_menu'], $lama['grup_menu'], $lama['filter_tag'], $lama['is_signature'], $hargaHot, $hargaIce, $gambar, $lama['deskripsi']);
    flash('sukses', "Menu '" . $lama['nama_menu'] . "' berhasil ditambahkan!");
    redirect('admin/menu');
}

function admin_menu_update() {
    menus_update_harga((int)post('menu_id'), admin_menu_ambil_harga('harga_hot'), admin_menu_ambil_harga('harga_ice'));
    flash('sukses', 'Harga menu diperbarui!');
    redirect('admin/menu');
}

function admin_menu_hapus() {
    menus_hapus((int)post('menu_id'));
    flash('sukses', 'Menu dihapus!');
    redirect('admin/menu');
}
?>
