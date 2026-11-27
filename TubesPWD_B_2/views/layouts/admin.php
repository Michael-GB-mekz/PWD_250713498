<?php
$flash = flash_ambil();
if (!isset($judul)) {
    $judul = 'Admin';
}

$menuAdmin = array(
    array('-', 'Penjualan'),
    array('kasir', 'Kasir (POS)', 'kasir', 'kasir'),
    array('riwayat', 'Riwayat Transaksi', 'kasir/riwayat', 'kasir'),
    array('pesanan', 'Sales Order', 'admin/pesanan', 'kasir'),
    array('dashboard', 'Dashboard', 'admin', 'admin'),
    array('menu', 'Kelola Menu', 'admin/menu', 'admin'),
    array('promo', 'Promo', 'admin/promo', 'admin'),
    array('pengaturan', 'Tampilan Toko', 'admin/pengaturan', 'admin')
);
$adalahAdmin = ($_SESSION['role'] == 'admin');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($judul); ?> - Admin Caritas Cafe</title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <script type="text/javascript" src="assets/js/script.js"></script>
    <?php if (isset($skripTambahan)): ?><script type="text/javascript" src="assets/js/<?php echo e($skripTambahan); ?>"></script><?php endif; ?>
</head>
<body>

<?php if ($flash): ?>
<div class="toast toast-<?php echo e($flash['jenis']); ?>"><?php echo e($flash['pesan']); ?></div>
<?php endif; ?>

<div class="admin-wrap">
    <div class="admin-side">
        <div class="admin-brand">
            <img src="assets/img/logo.png" alt="Caritas Cafe" style="width:50px; height:50px; border-radius:50%;" />
            <div>
                <strong>Caritas Cafe</strong>
                <span><?php echo $adalahAdmin ? 'Panel Admin' : 'Panel Kasir'; ?></span>
            </div>
        </div>
        <div class="admin-menu">
            <?php foreach ($menuAdmin as $m): ?>
                <?php if ($m[0] == '-'): ?>
                    <?php if ($adalahAdmin || $m[1] == 'Penjualan'): ?><div class="admin-menu-judul"><?php echo $m[1]; ?></div><?php endif; ?>
                <?php elseif ($adalahAdmin || $m[3] == 'kasir'): ?>
                    <a href="<?php echo url($m[2]); ?>" class="<?php if ($halamanAdmin == $m[0]) echo 'active'; ?>"><?php echo $m[1]; ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="admin-menu admin-menu-bawah">
            <a href="<?php echo url('home'); ?>">Buka Aplikasi Pelanggan</a>
            <a href="<?php echo url('logout'); ?>" data-konfirm="Yakin ingin keluar dari akun ini?" data-judul="Keluar" data-tombol="Ya, keluar">Keluar</a>
        </div>
    </div>

    <div class="admin-main">
        <h1 class="admin-judul"><?php echo e($judul); ?></h1>
        <?php echo $isi; ?>
    </div>
</div>

</body>
</html>
