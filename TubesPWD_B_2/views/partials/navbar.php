<?php
if (!isset($halaman)) {
    $halaman = '';
}
?>
<div class="navbar">
    <a href="<?php echo url('home'); ?>" class="nav-brand"><img src="assets/img/logo.png" alt="Caritas Cafe" /></a>

    <div class="nav-links">
        <a href="<?php echo url('home'); ?>" class="<?php if ($halaman == 'menu') echo 'active'; ?>">Menu</a>
        <a href="<?php echo url('promo'); ?>" class="<?php if ($halaman == 'promo') echo 'active'; ?>">Promo<?php if (jumlah_promo_baru() > 0): ?><span class="notif"><?php echo jumlah_promo_baru(); ?></span><?php endif; ?></a>
        <a href="<?php echo url('home', array(), 'tentang'); ?>">Tentang</a>
    </div>

    <div class="nav-actions">
        <?php if (sudah_login()): ?>
        <a href="<?php echo url('home', array('open_cart' => 1)); ?>" class="btn-outline" onclick="return bukaKeranjang();">Keranjang (<?php echo $jumlahKeranjang; ?>)</a>
        <?php endif; ?>
        <?php if (sudah_login()): ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo url('admin'); ?>" class="btn-outline">Panel Admin</a>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'kasir' || $_SESSION['role'] == 'admin'): ?>
                <a href="<?php echo url('kasir'); ?>" class="btn-outline">Kasir</a>
            <?php endif; ?>
            <?php if (adalah_staf()): ?>
            <a href="<?php echo url('logout'); ?>" class="btn-solid">Keluar</a>
            <?php else: ?>
            <a href="<?php echo url('profil'); ?>" class="btn-solid">
                <?php if (!empty($_SESSION['foto']) && file_exists(FOLDER_UPLOAD . $_SESSION['foto'])): ?>
                    <img src="<?php echo FOLDER_UPLOAD . e($_SESSION['foto']); ?>" alt="Foto" class="nav-avatar" />
                <?php endif; ?>
                <?php
                $namaDepan = explode(' ', $_SESSION['nama']);
                echo e($namaDepan[0]);
                ?>
            </a>
            <?php endif; ?>
        <?php else: ?>
            <a href="<?php echo url('login'); ?>" class="btn-solid">Masuk</a>
        <?php endif; ?>
    </div>
</div>
