<?php
function auth_form_login() {
    view('auth/login', array('judul' => 'Masuk - Caritas Cafe', 'error' => '', 'lama' => ''), 'auth');
}

function auth_proses_login() {
    $isian = post('login_id');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $error = '';
    $staf = staf_cari($isian);
    if ($staf) {
        $user = array('id' => $staf['id'], 'nama_lengkap' => $staf['nama'], 'role' => $staf['role'], 'foto_profil' => '');
        if (!hash_equals($staf['password'], $password)) {
            $error = 'Password salah!';
        }
    } else {
        $user = users_cari_login($isian);
        if (!$user) {
            $error = 'Akun tidak ditemukan!';
        } elseif (!password_verify($password, $user['password'])) {
            $error = 'Password salah!';
        }
    }

    if ($error != '') {
        view('auth/login', array('judul' => 'Masuk - Caritas Cafe', 'error' => $error, 'lama' => $isian), 'auth');
        return;
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama_lengkap'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['foto'] = $user['foto_profil'];

    keranjang_gabung_tamu();

    if ($user['role'] == 'admin') {
        redirect('admin');
    }
    if ($user['role'] == 'kasir') {
        redirect('kasir');
    }
    redirect('home');
}

function auth_form_register() {
    view('auth/register', array('judul' => 'Daftar - Caritas Cafe', 'skrip' => 'register.js', 'error' => '', 'lama' => array()), 'auth');
}

function auth_proses_register() {
    $nama = post('nama_lengkap');
    $username = post('username');
    $email = post('email');
    $noHp = post('no_hp');
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $error = '';
    if ($nama == '') {
        $error = 'Nama lengkap wajib diisi.';
    } elseif (!preg_match('/^[A-Za-z0-9_.]{3,30}$/', $username)) {
        $error = 'Username 3-30 karakter: huruf, angka, titik, atau underscore.';
    } elseif (!preg_match('/^[^\s@]+@[^\s@]+\.[^\s@]+$/', $email)) {
        $error = 'Format email tidak valid.';
    } elseif ($noHp != '' && !preg_match('/^[0-9+]{8,15}$/', $noHp)) {
        $error = 'Nomor WhatsApp harus 8-15 digit angka.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif (staf_cari($username) || users_username_atau_email_ada($username, $email)) {
        $error = 'Username atau Email sudah dipakai!';
    }

    if ($error == '') {
        $passwordAman = password_hash($password, PASSWORD_DEFAULT);
        $idBaru = users_buat($nama, $username, $email, $passwordAman, $noHp);
        if ($idBaru > 0) {
            promos_berikan_ke_user($idBaru);
            $pesan = 'Akun berhasil dibuat! Silakan masuk.';
            $promoBaru = promos_otomatis_aktif();
            if (count($promoBaru) > 0) {
                $pesan .= ' Kamu mendapat diskon ' . $promoBaru[0]['diskon_persen'] . '% selama ' . $promoBaru[0]['berlaku_hari'] . ' hari.';
            }
            flash('sukses', $pesan);
            redirect('login');
        }
        $error = 'Gagal membuat akun, coba lagi.';
    }

    $lama = array('nama_lengkap' => $nama, 'username' => $username, 'email' => $email, 'no_hp' => $noHp);
    view('auth/register', array('judul' => 'Daftar - Caritas Cafe', 'skrip' => 'register.js', 'error' => $error, 'lama' => $lama), 'auth');
}

function auth_logout() {
    session_unset();
    session_destroy();
    header('Location: ' . url('home'));
    exit();
}

function auth_cek_user() {
    header('Content-Type: application/json');
    $kolom = isset($_GET['field']) ? $_GET['field'] : '';
    $nilai = isset($_GET['value']) ? trim($_GET['value']) : '';

    $ada = false;
    if ($nilai != '') {
        $ada = users_kolom_ada($kolom, $nilai);
        if ($kolom == 'username' && staf_cari($nilai)) {
            $ada = true;
        }
    }
    echo json_encode(array('exists' => $ada));
}
?>
