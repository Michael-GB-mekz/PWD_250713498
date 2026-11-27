<?php
$flash = flash_ambil();
if (!isset($judul)) {
    $judul = 'Caritas Cafe';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="id">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo e($judul); ?></title>
    <link rel="stylesheet" type="text/css" href="assets/css/style.css" />
    <?php if (isset($skrip)): ?><script type="text/javascript" src="assets/js/<?php echo e($skrip); ?>"></script><?php endif; ?>
</head>
<body>

<?php if ($flash): ?>
<div class="toast toast-<?php echo e($flash['jenis']); ?>"><?php echo e($flash['pesan']); ?></div>
<?php endif; ?>

<div class="auth-wrap">
    <div class="auth-card">
        <img src="assets/img/logo.png" alt="Caritas Cafe" class="auth-logo" />
        <?php echo $isi; ?>
    </div>
</div>

</body>
</html>
