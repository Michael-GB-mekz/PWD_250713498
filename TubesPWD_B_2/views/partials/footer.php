<?php
global $dataLokasi;
?>
<div class="footer" id="tentang">
    <div class="footer-col wide">
        <strong>Tentang Caritas Cafe</strong>
        Unit usaha dari Koperasi Caritas UAJY yang menghadirkan ruang nyaman untuk ngopi, berdiskusi, dan belajar di lingkungan kampus. Menyajikan aneka kopi, minuman non-kopi, dan pastry dengan harga ramah mahasiswa. Tersedia di Kampus I, Kampus III, dan Perpustakaan Pusat UAJY (08.00 - 16.30 WIB).
    </div>
    <div class="footer-col">
        <strong>Jelajahi</strong>
        <a href="<?php echo url('home'); ?>">Menu</a>
        <a href="<?php echo url('promo'); ?>">Promo</a>
        <a href="<?php echo url('profil'); ?>">Akun Saya</a>
    </div>
    <div class="footer-col">
        <strong>Lokasi</strong>
        <?php foreach ($dataLokasi as $nama => $info): ?>
        <?php echo e($nama); ?><br />
        <?php endforeach; ?>
    </div>
</div>
