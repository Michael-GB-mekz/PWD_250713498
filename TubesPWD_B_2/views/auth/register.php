<?php
$lamaNama = isset($lama['nama_lengkap']) ? $lama['nama_lengkap'] : '';
$lamaUser = isset($lama['username']) ? $lama['username'] : '';
$lamaEmail = isset($lama['email']) ? $lama['email'] : '';
$lamaHp = isset($lama['no_hp']) ? $lama['no_hp'] : '';
?>
<h2>Daftar Member</h2>
<p class="auth-sub">Buat akun untuk mulai memesan.</p>

<?php if ($error != ''): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>

<form method="post" action="<?php echo url('register'); ?>">
    <input type="text" name="nama_lengkap" value="<?php echo e($lamaNama); ?>" placeholder="Nama Lengkap" class="input" />

    <input type="text" id="regUser" name="username" value="<?php echo e($lamaUser); ?>" placeholder="Username" class="input" onkeyup="cekUsername()" />
    <span id="msgUser"></span>

    <input type="text" id="regEmail" name="email" value="<?php echo e($lamaEmail); ?>" placeholder="Email" class="input" onkeyup="cekEmail()" />
    <span id="msgEmail"></span>

    <input type="text" id="regHp" name="no_hp" value="<?php echo e($lamaHp); ?>" placeholder="Nomor WhatsApp (boleh kosong)" class="input" onkeyup="cekHp()" />
    <span id="msgHp"></span>

    <input type="password" id="regPass" name="password" placeholder="Password (minimal 6 karakter)" class="input" onkeyup="cekPassword()" />
    <span id="msgPass"></span>

    <button type="submit" id="btnDaftar" class="btn-full">Daftar Sekarang</button>
</form>

<p class="auth-link">Sudah punya akun? <a href="<?php echo url('login'); ?>">Masuk</a></p>
