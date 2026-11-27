<div class="akun-menu">
    <div class="akun-kepala">
        <?php if (!empty($user['foto_profil']) && file_exists(FOLDER_UPLOAD . $user['foto_profil'])): ?>
            <img src="<?php echo FOLDER_UPLOAD . e($user['foto_profil']); ?>" alt="Foto profil" class="akun-foto" />
        <?php else: ?>
            <div class="akun-foto akun-foto-kosong"><?php echo strtoupper(substr($user['nama_lengkap'], 0, 2)); ?></div>
        <?php endif; ?>
        <div>
            <strong><?php echo e($user['nama_lengkap']); ?></strong>
            <small><?php echo e($user['email']); ?></small>
        </div>
    </div>

    <div class="akun-links">
        <a href="<?php echo url('profil'); ?>" class="<?php if ($subhalaman == 'profil') echo 'active'; ?>">Akun Saya</a>
        <a href="<?php echo url('profil/pesanan'); ?>" class="<?php if ($subhalaman == 'pesanan') echo 'active'; ?>">Pesanan Saya</a>
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <a href="<?php echo url('admin'); ?>">Panel Admin</a>
        <?php endif; ?>
        <?php if ($_SESSION['role'] == 'kasir'): ?>
            <a href="<?php echo url('kasir'); ?>">Panel Kasir</a>
        <?php endif; ?>
        <a href="<?php echo url('logout'); ?>" class="akun-keluar" data-konfirm="Yakin ingin keluar dari akun ini?" data-judul="Keluar" data-tombol="Ya, keluar">Keluar</a>
    </div>
</div>
