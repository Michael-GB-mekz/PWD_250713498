<h2>Selamat datang</h2>
<p class="auth-sub">Masuk untuk memesan kopi tanpa antre.</p>

<?php if ($error != ''): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo url('login'); ?>">
    <input type="text" name="login_id" value="<?php echo e($lama); ?>" placeholder="Email, No. HP, atau Username" class="input" />
    <input type="password" name="password" placeholder="Password" class="input" />
    <button type="submit" class="btn-full">Masuk</button>
</form>

<a href="<?php echo url('home'); ?>" class="btn-full light" style="margin-top:10px;">Lanjut sebagai Tamu</a>

<p class="auth-link">Belum punya akun? <a href="<?php echo url('register'); ?>">Daftar</a></p>
