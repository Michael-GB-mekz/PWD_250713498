<?php
function e($teks) {
    return htmlspecialchars((string)$teks, ENT_QUOTES);
}

function url($rute = 'home', $param = array(), $jangkar = '') {
    $alamat = 'index.php?r=' . $rute;
    foreach ($param as $kunci => $nilai) {
        $alamat .= '&' . $kunci . '=' . urlencode($nilai);
    }
    if ($jangkar != '') {
        $alamat .= '#' . $jangkar;
    }
    return $alamat;
}

function redirect($rute = 'home', $param = array(), $jangkar = '') {
    header('Location: ' . url($rute, $param, $jangkar));
    exit();
}

function rupiah($angka) {
    return 'Rp' . number_format($angka, 0, ',', '.');
}

function harga_termurah($menu) {
    $daftar = array();
    if ($menu['harga_hot']) {
        $daftar[] = (int)$menu['harga_hot'];
    }
    if ($menu['harga_ice']) {
        $daftar[] = (int)$menu['harga_ice'];
    }
    if (count($daftar) == 0) {
        return 0;
    }
    return min($daftar);
}

function harga_kartu($menu) {
    $harga = harga_termurah($menu);
    if ($harga == 0) {
        return '-';
    }
    return rupiah($harga);
}

function harga_kartu_html($menu) {
    $harga = harga_termurah($menu);
    if ($harga == 0) {
        return '-';
    }
    return harga_html($harga);
}

function adalah_roti($grup) {
    return $grup == 'CROISSANT' || $grup == 'SOURDOUGH' || $grup == 'JOGJA BUN';
}

function terjual_menu() {
    static $peta = null;
    if ($peta === null) {
        $peta = orders_terjual_per_menu();
    }
    return $peta;
}

function menu_terlaris($menuId) {
    $peta = terjual_menu();
    $urut = array_keys($peta);
    $tiga = array_slice($urut, 0, 3);
    return in_array($menuId, $tiga);
}

function promo_user() {
    static $dimuat = false;
    static $promo = null;
    if (!$dimuat) {
        $dimuat = true;
        if (sudah_login()) {
            $promo = promos_aktif_user((int)$_SESSION['user_id']);
        }
    }
    return $promo;
}

function jumlah_promo_baru() {
    static $dimuat = false;
    static $jumlah = 0;
    if (!$dimuat) {
        $dimuat = true;
        if (sudah_login() && !adalah_staf()) {
            $jumlah = promos_jumlah_bisa_diklaim((int)$_SESSION['user_id']);
        }
    }
    return $jumlah;
}

function harga_html($harga) {
    return rupiah($harga);
}

function sisa_waktu($tanggal) {
    $selisih = strtotime($tanggal) - time();
    if ($selisih <= 0) {
        return 'sudah berakhir';
    }
    $hari = floor($selisih / 86400);
    $jam = floor(($selisih % 86400) / 3600);
    if ($hari > 0) {
        return $hari . ' hari ' . $jam . ' jam lagi';
    }
    $menit = floor(($selisih % 3600) / 60);
    return $jam . ' jam ' . $menit . ' menit lagi';
}

function tanggal_jam($teks) {
    return tanggal($teks) . ' ' . date('H.i', strtotime($teks));
}

function post($kunci, $bawaan = '') {
    if (isset($_POST[$kunci])) {
        return trim($_POST[$kunci]);
    }
    return $bawaan;
}

function flash($jenis, $pesan) {
    $_SESSION['flash'] = array('jenis' => $jenis, 'pesan' => $pesan);
}

function flash_ambil() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function sudah_login() {
    return isset($_SESSION['user_id']);
}

function staf_cari($username) {
    global $akunStaf;
    foreach ($akunStaf as $staf) {
        if (strtolower($staf['username']) == strtolower($username)) {
            return $staf;
        }
    }
    return null;
}

function nama_staf($id) {
    global $akunStaf;
    foreach ($akunStaf as $staf) {
        if ($staf['id'] == $id) {
            return $staf['nama'];
        }
    }
    return '-';
}

function adalah_staf() {
    return sudah_login() && $_SESSION['user_id'] < 0;
}

function cek_login() {
    if (!sudah_login()) {
        redirect('login');
    }
}

function cek_admin() {
    cek_login();
    if ($_SESSION['role'] != 'admin') {
        redirect('home');
    }
}

function cek_kasir() {
    cek_login();
    if ($_SESSION['role'] != 'kasir' && $_SESSION['role'] != 'admin') {
        redirect('home');
    }
}

function role_adalah($role) {
    return sudah_login() && $_SESSION['role'] == $role;
}

function angka($nilai) {
    $teks = rtrim(rtrim(number_format((float)$nilai, 2, ',', '.'), '0'), ',');
    return $teks;
}

function tanggal($teks) {
    if ($teks == '' || $teks == null) {
        return '-';
    }
    $bulan = array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
    $waktu = strtotime($teks);
    return date('j', $waktu) . ' ' . $bulan[(int)date('n', $waktu) - 1] . ' ' . date('Y', $waktu);
}

function view($nama, $data = array(), $layout = 'main') {
    extract($data);
    ob_start();
    require 'views/' . $nama . '.php';
    $isi = ob_get_clean();
    require 'views/layouts/' . $layout . '.php';
}
?>
