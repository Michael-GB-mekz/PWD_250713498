<?php
function admin_dashboard_index() {
    $ringkasan = orders_ringkasan_hari_ini();

    $data = array(
        'halamanAdmin' => 'dashboard',
        'judul'        => 'Dashboard',
        'pesananHari'  => (int)$ringkasan['jumlah'],
        'omzetHari'    => (int)$ringkasan['omzet'],
        'pesananAktif' => orders_jumlah_aktif()
    );
    view('admin/dashboard', $data, 'admin');
}
?>
