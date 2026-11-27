<?php
$flash = flash_ambil();
$jumlahKeranjang = keranjang_jumlah();
$bukaKeranjang = isset($_GET['open_cart']);
if (!isset($judul)) {
    $judul = 'Caritas Cafe UAJY';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($judul); ?></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <script type="text/javascript" src="assets/js/script.js"></script>
</head>
<body class="halaman">

<?php require 'views/partials/navbar.php'; ?>

<?php if ($flash && !$bukaKeranjang): ?>
<div class="toast toast-<?php echo e($flash['jenis']); ?>"><?php echo e($flash['pesan']); ?></div>
<?php endif; ?>

<?php echo $isi; ?>

<?php require 'views/partials/footer.php'; ?>

<?php if ($jumlahKeranjang > 0): ?>
<button type="button" class="cart-float" onclick="bukaKeranjang()">
    <span>Keranjang - <?php echo $jumlahKeranjang; ?> item</span>
    <span><?php echo rupiah(keranjang_total_bayar()); ?></span>
</button>
<?php endif; ?>

<?php require 'views/cart/modal.php'; ?>

</body>
</html>
