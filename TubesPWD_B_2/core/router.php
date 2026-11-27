<?php
$daftarRoute = array();

function route($metode, $jalur, $nama, $aksi, $akses = '') {
    global $daftarRoute;
    $daftarRoute[$metode . ' ' . $jalur] = array('nama' => $nama, 'aksi' => $aksi, 'akses' => $akses);
}

function jalankan_route() {
    global $daftarRoute;

    $jalur = 'home';
    if (isset($_GET['r'])) {
        $jalur = $_GET['r'];
    } elseif (!sudah_login()) {
        redirect('menu');
    }
    if ($jalur != 'logout' && sudah_login() && $_SESSION['user_id'] > 0 && !users_cari((int)$_SESSION['user_id'])) {
        session_unset();
        flash('error', 'Sesi login sudah tidak berlaku, silakan masuk lagi.');
        redirect('login');
    }

    $kunci = $_SERVER['REQUEST_METHOD'] . ' ' . $jalur;

    if (!isset($daftarRoute[$kunci])) {
        http_response_code(404);
        view('error/404', array('halaman' => ''));
        return;
    }

    $route = $daftarRoute[$kunci];

    if ($route['akses'] == 'login') {
        cek_login();
    }
    if ($route['akses'] == 'admin') {
        cek_admin();
    }
    if ($route['akses'] == 'kasir') {
        cek_kasir();
    }

    require_once 'controllers/' . $route['nama'] . '_controller.php';
    $fungsi = $route['nama'] . '_' . $route['aksi'];
    $fungsi();
}
?>
