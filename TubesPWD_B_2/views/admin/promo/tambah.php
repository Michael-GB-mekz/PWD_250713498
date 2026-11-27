<?php
$aksiForm = url('admin/promo/tambah');
$idPromo = '';
$teksTombol = 'Simpan Promo';
?>
<p style="margin-bottom:14px;"><a href="<?php echo url('admin/promo'); ?>" class="back-link">&larr; Kembali ke daftar promo</a></p>

<div class="white-card form-sempit">
    <?php require 'views/admin/promo/_form.php'; ?>
</div>
