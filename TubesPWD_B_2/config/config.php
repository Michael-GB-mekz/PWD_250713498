<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAMA', 'db_caritas_d2');

$akunStaf = array(
    array('id' => -1, 'username' => 'admin', 'password' => 'admin123', 'nama' => 'Admin Caritas', 'role' => 'admin'),
    array('id' => -2, 'username' => 'kasir', 'password' => 'kasir123', 'nama' => 'Kasir Caritas', 'role' => 'kasir')
);

define('FOLDER_UPLOAD', 'uploads/');

$dataLokasi = array(
    'Caritas - Kampus 1 UAJY' => array('lat' => -7.7790, 'lon' => 110.3855, 'peta' => 'Kampus 1 UAJY Mrican Yogyakarta'),
    'Caritas - Kampus 3 UAJY' => array('lat' => -7.77954, 'lon' => 110.41582, 'peta' => 'Kampus 3 UAJY Babarsari Yogyakarta'),
    'Caritas - Perpustakaan Pusat UAJY' => array('lat' => -7.78091, 'lon' => 110.41435, 'peta' => 'Perpustakaan Pusat UAJY Yogyakarta')
);
?>
