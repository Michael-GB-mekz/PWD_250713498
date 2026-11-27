<?php
function admin_promo_cek($nama, $persen, $hari, $batas, $jenis, $kuota) {
    if ($nama == '') {
        return 'Nama promo wajib diisi.';
    }
    if (!ctype_digit($persen) || $persen < 1 || $persen > 100) {
        return 'Diskon harus berupa angka 1 sampai 100 persen.';
    }
    if (!ctype_digit($hari) || $hari < 1 || $hari > 365) {
        return 'Masa berlaku harus berupa angka 1 sampai 365 hari.';
    }
    if (!ctype_digit($batas) || $batas > 99) {
        return 'Batas pemakaian harus berupa angka 0 sampai 99 (0 berarti tanpa batas).';
    }
    if ($jenis != 'otomatis' && $jenis != 'klaim') {
        return 'Jenis promo tidak valid.';
    }
    if (!ctype_digit($kuota) || $kuota > 100000) {
        return 'Kuota harus berupa angka 0 sampai 100000 (0 berarti tanpa batas).';
    }
    return '';
}

function admin_promo_ambil_batas_klaim() {
    $teks = post('klaim_sampai');
    if ($teks == '') {
        return null;
    }
    if (!preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $teks)) {
        return false;
    }
    return str_replace('T', ' ', $teks) . ':00';
}

function admin_promo_baca_form() {
    $jenis = post('jenis');
    $kuota = post('kuota', '0');
    $error = admin_promo_cek(post('nama_promo'), post('diskon_persen'), post('berlaku_hari'), post('batas_pakai'), $jenis, $kuota);
    $batasKlaim = admin_promo_ambil_batas_klaim();
    if ($error == '' && $batasKlaim === false) {
        $error = 'Format batas klaim tidak valid.';
    }

    if ($jenis == 'otomatis') {
        $kuota = '0';
        $batasKlaim = null;
    }

    $aktif = 0;
    if (isset($_POST['aktif'])) {
        $aktif = 1;
    }
    $data = array(
        'nama'     => post('nama_promo'),
        'deskripsi' => post('deskripsi'),
        'persen'   => (int)post('diskon_persen'),
        'hari'     => (int)post('berlaku_hari'),
        'batas'    => (int)post('batas_pakai'),
        'aktif'    => $aktif,
        'jenis'    => $jenis,
        'kuota'    => (int)$kuota,
        'klaim'    => $batasKlaim
    );
    return array($error, $data);
}

function admin_promo_index() {
    $data = array(
        'halamanAdmin' => 'promo',
        'judul'        => 'Promo',
        'daftar'       => promos_semua_admin()
    );
    view('admin/promo/index', $data, 'admin');
}

function admin_promo_tampil_form($nilai) {
    view('admin/promo/tambah', array('halamanAdmin' => 'promo', 'judul' => 'Tambah Promo', 'nilai' => $nilai), 'admin');
}

function admin_promo_form_tambah() {
    admin_promo_tampil_form(array(
        'nama_promo' => '', 'deskripsi' => '', 'jenis' => 'klaim', 'diskon_persen' => '', 'berlaku_hari' => '',
        'batas_pakai' => '1', 'kuota' => '0', 'klaim_sampai' => '', 'aktif' => 1
    ));
}

function admin_promo_tambah() {
    $hasil = admin_promo_baca_form();
    if ($hasil[0] != '') {
        flash('error', $hasil[0]);
        admin_promo_tampil_form(array(
            'nama_promo'    => post('nama_promo'),
            'deskripsi'     => post('deskripsi'),
            'jenis'         => post('jenis'),
            'diskon_persen' => post('diskon_persen'),
            'berlaku_hari'  => post('berlaku_hari'),
            'batas_pakai'   => post('batas_pakai'),
            'kuota'         => post('kuota'),
            'klaim_sampai'  => post('klaim_sampai'),
            'aktif'         => isset($_POST['aktif']) ? 1 : 0
        ));
        return;
    }
    $d = $hasil[1];
    promos_tambah($d['nama'], $d['deskripsi'], $d['persen'], $d['hari'], $d['batas'], $d['aktif'], $d['jenis'], $d['kuota'], $d['klaim']);
    flash('sukses', "Promo '" . $d['nama'] . "' berhasil ditambahkan.");
    redirect('admin/promo');
}

function admin_promo_edit() {
    $promo = promos_cari((int)(isset($_GET['id']) ? $_GET['id'] : 0));
    if (!$promo) {
        flash('error', 'Promo tidak ditemukan.');
        redirect('admin/promo');
    }
    view('admin/promo/edit', array('halamanAdmin' => 'promo', 'judul' => 'Ubah Promo', 'promo' => $promo), 'admin');
}

function admin_promo_update() {
    $id = (int)post('id');
    $hasil = admin_promo_baca_form();
    if ($hasil[0] != '') {
        flash('error', $hasil[0]);
        redirect('admin/promo/edit', array('id' => $id));
    }
    $d = $hasil[1];
    promos_update($id, $d['nama'], $d['deskripsi'], $d['persen'], $d['hari'], $d['batas'], $d['aktif'], $d['jenis'], $d['kuota'], $d['klaim']);
    flash('sukses', 'Promo diperbarui.');
    redirect('admin/promo');
}

function admin_promo_hapus() {
    promos_hapus((int)post('id'));
    flash('sukses', 'Promo dihapus.');
    redirect('admin/promo');
}

function admin_promo_berikan() {
    $promo = promos_cari((int)post('id'));
    if (!$promo) {
        flash('error', 'Promo tidak ditemukan.');
        redirect('admin/promo');
    }
    $jumlah = promos_berikan_ke_semua($promo['id']);
    flash('sukses', "Promo '" . $promo['nama_promo'] . "' diberikan ke " . $jumlah . ' akun.');
    redirect('admin/promo');
}
?>
