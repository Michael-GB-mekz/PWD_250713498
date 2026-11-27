<?php
function profile_tampil($user, $error) {
    $data = array(
        'halaman' => 'akun',
        'judul'   => 'Akun Saya - Caritas Cafe',
        'user'    => $user,
        'error'   => $error
    );
    view('profile/index', $data);
}

function profile_cek_pelanggan() {
    if (adalah_staf()) {
        redirect($_SESSION['role'] == 'admin' ? 'admin' : 'kasir');
    }
}

function profile_index() {
    profile_cek_pelanggan();
    profile_tampil(users_cari((int)$_SESSION['user_id']), '');
}

function profile_pesanan() {
    profile_cek_pelanggan();
    $userId = (int)$_SESSION['user_id'];
    $data = array(
        'halaman' => 'akun',
        'judul'   => 'Pesanan Saya - Caritas Cafe',
        'user'    => users_cari($userId),
        'riwayat' => orders_milik_user($userId)
    );
    view('profile/pesanan', $data);
}

function profile_simpan() {
    profile_cek_pelanggan();
    $userId = (int)$_SESSION['user_id'];
    $nama = post('nama_lengkap');
    $email = post('email');
    $noHp = post('no_hp');

    $error = '';
    if ($nama == '') {
        $error = 'Nama lengkap wajib diisi.';
    } elseif (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
        $error = 'Format email tidak valid.';
    } elseif ($noHp != '' && !preg_match('/^[0-9+]{8,15}$/', $noHp)) {
        $error = 'Nomor WhatsApp harus 8-15 digit angka.';
    } elseif (users_email_dipakai_lain($email, $userId)) {
        $error = 'Email sudah dipakai akun lain.';
    }

    $fotoBaru = '';
    if ($error == '' && isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $ekstensi = strtolower(pathinfo($_FILES['foto_profil']['name'], PATHINFO_EXTENSION));
        if ($ekstensi != 'jpg' && $ekstensi != 'jpeg' && $ekstensi != 'png' && $ekstensi != 'webp') {
            $error = 'Foto harus berformat JPG, PNG, atau WEBP.';
        } elseif ($_FILES['foto_profil']['size'] > 2 * 1024 * 1024) {
            $error = 'Ukuran foto maksimal 2 MB.';
        } else {
            if (!is_dir(FOLDER_UPLOAD)) {
                mkdir(FOLDER_UPLOAD);
            }
            $fotoBaru = 'user_' . $userId . '_' . time() . '.' . $ekstensi;
            if (!move_uploaded_file($_FILES['foto_profil']['tmp_name'], FOLDER_UPLOAD . $fotoBaru)) {
                $fotoBaru = '';
                $error = 'Foto gagal disimpan.';
            }
        }
    }

    if ($error != '') {
        $user = users_cari($userId);
        $user['nama_lengkap'] = $nama;
        $user['email'] = $email;
        $user['no_hp'] = $noHp;
        profile_tampil($user, $error);
        return;
    }

    if ($fotoBaru != '') {
        if (!empty($_SESSION['foto']) && file_exists(FOLDER_UPLOAD . $_SESSION['foto'])) {
            unlink(FOLDER_UPLOAD . $_SESSION['foto']);
        }
        users_update_foto($userId, $fotoBaru);
        $_SESSION['foto'] = $fotoBaru;
    }

    users_update_profil($userId, $nama, $email, $noHp);
    $_SESSION['nama'] = $nama;

    flash('sukses', 'Profil berhasil diperbarui!');
    redirect('profil');
}
?>
