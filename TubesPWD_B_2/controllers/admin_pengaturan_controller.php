<?php
function admin_pengaturan_hapus_file($sumber) {
    if (strpos($sumber, FOLDER_UPLOAD . 'hero_') === 0 && file_exists($sumber)) {
        unlink($sumber);
    }
}

function admin_pengaturan_index() {
    $data = array(
        'halamanAdmin' => 'pengaturan',
        'judul'        => 'Tampilan Toko',
        'media'        => hero_semua(),
        'detik'        => (int)pengaturan_ambil('hero_detik', '4')
    );
    view('admin/pengaturan/index', $data, 'admin');
}

function admin_pengaturan_form_tambah() {
    view('admin/pengaturan/tambah', array('halamanAdmin' => 'pengaturan', 'judul' => 'Tambah Foto atau Video'), 'admin');
}

function admin_pengaturan_media_tambah() {
    if ($_SERVER['CONTENT_LENGTH'] > 0 && count($_POST) == 0 && count($_FILES) == 0) {
        flash('error', 'File terlalu besar untuk server ini (batas ' . ini_get('post_max_size') . ').');
        redirect('admin/pengaturan/tambah');
    }

    $alamat = post('url_media');
    $adaFile = isset($_FILES['file_media']) && $_FILES['file_media']['error'] != UPLOAD_ERR_NO_FILE;

    if ($adaFile) {
        if ($_FILES['file_media']['error'] != 0) {
            flash('error', 'File gagal diunggah (kemungkinan lebih besar dari batas server ' . ini_get('upload_max_filesize') . ').');
            redirect('admin/pengaturan/tambah');
        }
        $nama = $_FILES['file_media']['name'];
        $jenis = hero_jenis_dari_nama($nama);
        if ($jenis == '') {
            flash('error', 'Format harus JPG, PNG, WEBP (foto) atau MP4, WEBM (video).');
            redirect('admin/pengaturan/tambah');
        }
        $batas = 3;
        if ($jenis == 'video') {
            $batas = 30;
        }
        if ($_FILES['file_media']['size'] > $batas * 1024 * 1024) {
            flash('error', 'Ukuran ' . $jenis . ' maksimal ' . $batas . ' MB.');
            redirect('admin/pengaturan/tambah');
        }
        if (!is_dir(FOLDER_UPLOAD)) {
            mkdir(FOLDER_UPLOAD);
        }
        $ekstensi = strtolower(pathinfo($nama, PATHINFO_EXTENSION));
        $namaBaru = FOLDER_UPLOAD . 'hero_' . uniqid() . '.' . $ekstensi;
        if (!move_uploaded_file($_FILES['file_media']['tmp_name'], $namaBaru)) {
            flash('error', 'File gagal disimpan.');
            redirect('admin/pengaturan/tambah');
        }
        hero_tambah($namaBaru, $jenis);
        flash('sukses', ucfirst($jenis) . ' ditambahkan ke slideshow.');
        redirect('admin/pengaturan');
    }

    if ($alamat != '') {
        if (!preg_match('/^https?:\/\/\S+$/', $alamat) || strlen($alamat) > 250) {
            flash('error', 'Alamat harus diawali http:// atau https:// (maksimal 250 karakter).');
            redirect('admin/pengaturan/tambah');
        }
        $jenis = hero_jenis_dari_nama($alamat);
        if ($jenis == '') {
            flash('error', 'Alamat harus berakhiran .jpg, .png, .webp (foto) atau .mp4, .webm (video).');
            redirect('admin/pengaturan/tambah');
        }
        hero_tambah($alamat, $jenis);
        flash('sukses', ucfirst($jenis) . ' ditambahkan ke slideshow.');
        redirect('admin/pengaturan');
    }

    flash('error', 'Pilih file atau isi alamat terlebih dahulu.');
    redirect('admin/pengaturan/tambah');
}

function admin_pengaturan_media_hapus() {
    $media = hero_cari((int)post('id'));
    if ($media) {
        admin_pengaturan_hapus_file($media['sumber']);
        hero_hapus($media['id']);
        flash('sukses', 'Dihapus dari slideshow.');
    }
    redirect('admin/pengaturan');
}

function admin_pengaturan_detik() {
    $detik = post('detik');
    if (!ctype_digit($detik) || $detik < 2 || $detik > 15) {
        flash('error', 'Lama tampil harus berupa angka 2 sampai 15 detik.');
        redirect('admin/pengaturan');
    }
    pengaturan_simpan('hero_detik', $detik);
    flash('sukses', 'Lama tampil foto diperbarui.');
    redirect('admin/pengaturan');
}
?>
