<?php
$subhalaman = 'profil';
?>
<div class="akun">
    <?php require 'views/profile/_menu.php'; ?>

    <div class="akun-isi">
        <h2 style="margin-bottom:14px;">Akun Saya</h2>

        <?php if ($error != ''): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>

        <form method="post" action="<?php echo url('profil'); ?>" enctype="multipart/form-data" class="white-card">
            <div class="avatar-box">
                <?php if (!empty($user['foto_profil']) && file_exists(FOLDER_UPLOAD . $user['foto_profil'])): ?>
                    <img src="<?php echo FOLDER_UPLOAD . e($user['foto_profil']); ?>" alt="Foto profil" class="avatar" />
                <?php else: ?>
                    <div class="avatar-empty"><?php echo strtoupper(substr($user['nama_lengkap'], 0, 2)); ?></div>
                <?php endif; ?>
                <span class="opt-label">Ganti foto profil</span>
                <input type="file" name="foto_profil" accept="image/jpeg,image/png,image/webp" />
            </div>

            <span class="opt-label">Nama Lengkap</span>
            <input type="text" name="nama_lengkap" value="<?php echo e($user['nama_lengkap']); ?>" class="input" />

            <span class="opt-label">Email</span>
            <input type="text" name="email" value="<?php echo e($user['email']); ?>" class="input" />

            <span class="opt-label">No. WhatsApp</span>
            <input type="text" name="no_hp" value="<?php echo e($user['no_hp']); ?>" class="input" />

            <button type="submit" class="btn-full">Simpan Perubahan</button>
        </form>
    </div>
</div>
