<?php
date_default_timezone_set('Asia/Jakarta');
session_start();

require 'config/config.php';
require 'core/database.php';
require 'core/helpers.php';
require 'core/router.php';

foreach (glob('models/*_model.php') as $fileModel) {
    require_once $fileModel;
}

if (!isset($_SESSION['lokasi_pilihan'])) {
    $_SESSION['lokasi_pilihan'] = 'Caritas - Kampus 3 UAJY';
}
if (!isset($_SESSION['jarak_info'])) {
    $_SESSION['jarak_info'] = 'Manual';
}

require 'routes.php';

jalankan_route();
?>
