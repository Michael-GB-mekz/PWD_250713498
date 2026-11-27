<?php
$staf = adalah_staf();
$pelanggan = sudah_login() && !$staf;
$kelasStatus = array('Aktif' => 'badge-hijau', 'Kedaluwarsa' => 'badge-merah', 'Sudah dipakai' => 'badge-abu', 'Dinonaktifkan' => 'badge-abu');
?>
<div class="page-head page-head-tengah">
    <div class="container">
        <h1>Promo</h1>
        <p>Diskon spesial untuk pelanggan Caritas Cafe.</p>
    </div>
</div>

<div class="container promo-halaman" style="padding-top:26px; padding-bottom:40px;">

    <h2 style="margin-bottom:14px;"><?php echo $staf ? 'Promo yang sedang berjalan' : 'Promo yang bisa kamu klaim'; ?></h2>

    <?php if (count($bisaKlaim) == 0): ?>
    <div class="white-card" style="text-align:center; padding:30px 18px; margin-bottom:26px;">
        <strong style="font-size:17px;">Belum ada promo yang bisa diklaim</strong>
        <p class="muted" style="margin-top:6px;">Pantau terus halaman ini, promo baru akan muncul di sini.</p>
    </div>
    <?php endif; ?>

    <?php foreach ($bisaKlaim as $p): ?>
    <?php
    $habis = ($p['kuota'] > 0 && $p['terklaim'] >= $p['kuota']);
    $sisaKuota = $p['kuota'] - $p['terklaim'];
    ?>
    <div class="promo-kartu<?php if ($habis && !$p['sudah_klaim']) echo ' promo-mati'; ?>">
        <div class="promo-persen"><?php echo (int)$p['diskon_persen']; ?>%</div>
        <div class="promo-isi">
            <h3><?php echo e($p['nama_promo']); ?></h3>
            <p><?php echo e($p['deskripsi']); ?></p>
            <div class="promo-tenggat">
                Berlaku <?php echo (int)$p['berlaku_hari']; ?> hari setelah diklaim.
                <?php if ($p['batas_pakai'] == 0): ?>Bisa dipakai berkali-kali.<?php else: ?>Untuk <?php echo (int)$p['batas_pakai']; ?> kali pesanan.<?php endif; ?>
            </div>
            <p style="margin:6px 0 0;">
                <?php if ($p['klaim_sampai'] != null): ?>Klaim sebelum <?php echo tanggal_jam($p['klaim_sampai']); ?> (<?php echo e(sisa_waktu($p['klaim_sampai'])); ?>).<?php endif; ?>
                <?php if ($p['kuota'] > 0): ?>
                    <?php if ($habis): ?><strong>Kuota habis.</strong><?php else: ?><strong>Tersisa <?php echo (int)$sisaKuota; ?> dari <?php echo (int)$p['kuota']; ?> kuota.</strong><?php endif; ?>
                <?php endif; ?>
            </p>
        </div>

        <?php if ($p['sudah_klaim']): ?>
            <span class="badge badge-hijau">Sudah diklaim</span>
        <?php elseif ($habis): ?>
            <span class="badge badge-abu">Kuota habis</span>
        <?php elseif ($pelanggan): ?>
            <form method="post" action="<?php echo url('promo/klaim'); ?>">
                <input type="hidden" name="promo_id" value="<?php echo (int)$p['id']; ?>" />
                <button type="submit" class="btn-solid">Klaim Promo</button>
            </form>
        <?php elseif (!$staf): ?>
            <a href="<?php echo url('login'); ?>" class="btn-solid">Masuk untuk Klaim</a>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

    <?php if ($staf): ?>
    <?php elseif ($pelanggan): ?>
        <h2 style="margin:30px 0 14px;">Promo Saya</h2>

        <?php if (count($promoSaya) == 0): ?>
        <div class="white-card" style="text-align:center; padding:30px 18px;">
            <strong style="font-size:17px;">Belum ada promo di akunmu</strong>
            <p class="muted" style="margin-top:6px;">Klaim promo di atas, lalu promonya muncul di sini.</p>
        </div>
        <?php endif; ?>

        <?php foreach ($promoSaya as $p): ?>
        <div class="promo-kartu<?php if ($p['status'] != 'Aktif') echo ' promo-mati'; ?>">
            <div class="promo-persen"><?php echo (int)$p['diskon_persen']; ?>%</div>
            <div class="promo-isi">
                <h3><?php echo e($p['nama_promo']); ?> <span class="badge <?php echo $kelasStatus[$p['status']]; ?>"><?php echo e($p['status']); ?></span></h3>
                <p><?php echo e($p['deskripsi']); ?></p>
                <div class="promo-tenggat">
                    Berlaku sampai <?php echo tanggal_jam($p['berlaku_sampai']); ?>
                    <?php if ($p['status'] == 'Aktif'): ?>(<?php echo e(sisa_waktu($p['berlaku_sampai'])); ?>)<?php endif; ?>
                </div>
                <p style="margin:6px 0 0;">
                    <?php if ($p['batas_pakai'] == 0): ?>Bisa dipakai berkali-kali selama masa berlaku.
                    <?php else: ?>Dipakai <?php echo (int)$p['jumlah_dipakai']; ?> dari <?php echo (int)$p['batas_pakai']; ?> kali.<?php endif; ?>
                </p>
            </div>
            <?php if ($p['status'] == 'Aktif'): ?>
            <a href="<?php echo url('home', array(), 'menu'); ?>" class="btn-solid">Pesan Sekarang</a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>

    <?php else: ?>
        <?php if (count($definisi) > 0): ?>
        <h2 style="margin:30px 0 14px;">Hadiah untuk akun baru</h2>
        <?php foreach ($definisi as $p): ?>
        <div class="promo-kartu">
            <div class="promo-persen"><?php echo (int)$p['diskon_persen']; ?>%</div>
            <div class="promo-isi">
                <h3><?php echo e($p['nama_promo']); ?></h3>
                <p><?php echo e($p['deskripsi']); ?></p>
                <div class="promo-tenggat">
                    Berlaku <?php echo (int)$p['berlaku_hari']; ?> hari sejak akun dibuat.
                    <?php if ($p['batas_pakai'] == 0): ?>Bisa dipakai berkali-kali.<?php else: ?>Untuk <?php echo (int)$p['batas_pakai']; ?> kali pesanan.<?php endif; ?>
                </div>
            </div>
            <a href="<?php echo url('register'); ?>" class="btn-solid">Daftar Gratis</a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <p class="muted" style="margin-top:14px;">Sudah punya akun? <a href="<?php echo url('login'); ?>" style="font-weight:600;">Masuk</a> untuk mengklaim promo dan melihat promo milikmu.</p>
    <?php endif; ?>
</div>
