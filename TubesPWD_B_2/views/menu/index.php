<?php
global $dataLokasi;

$promoSaya = promo_user();
$promoTawar = null;
if (!$promoSaya && !sudah_login() && count($definisiPromo) > 0) {
    $promoTawar = $definisiPromo[0];
}
?>

<div class="hero">
    <div class="hero-text">
        <span class="hero-tag">Cafe Caritas &bull; Est 2024</span>
        <h1>Pesan kopi favoritmu, ambil tanpa antre.</h1>
        <p>Pesan dari kelas, bayar pakai QRIS, lalu ambil di Caritas saat pesananmu siap.</p>
        <div class="hero-btns">
            <a href="#menu" class="btn-solid">Pesan Sekarang</a>
            <a href="<?php echo url('promo'); ?>" class="btn-outline">Lihat Promo</a>
        </div>
    </div>
    <div class="hero-img-box">
        <div class="hero-slides" id="heroSlides" data-detik="<?php echo $detikHero; ?>">
            <?php foreach ($mediaHero as $i => $m): ?>
                <?php if ($m['jenis'] == 'video'): ?>
                <video src="<?php echo e($m['sumber']); ?>" class="hero-img hero-slide<?php if ($i == 0) echo ' aktif'; ?>" muted="muted" playsinline="playsinline" preload="auto"></video>
                <?php else: ?>
                <img src="<?php echo e($m['sumber']); ?>" alt="Kopi Caritas" class="hero-img hero-slide<?php if ($i == 0) echo ' aktif'; ?>" />
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php if (count($mediaHero) > 1): ?>
        <div class="hero-titik-box">
            <?php foreach ($mediaHero as $i => $m): ?>
            <button type="button" class="hero-titik<?php if ($i == 0) echo ' aktif'; ?>" onclick="pilihSlide(<?php echo $i; ?>)" title="Slide <?php echo $i + 1; ?>"></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <?php if ($promoSaya): ?>
        <div class="glass-badge">
            <span class="badge-pct"><?php echo (int)$promoSaya['diskon_persen']; ?>%</span>
            <span class="badge-text"><strong><?php echo e($promoSaya['nama_promo']); ?></strong><small>Berlaku <?php echo e(sisa_waktu($promoSaya['berlaku_sampai'])); ?></small></span>
        </div>
        <?php elseif ($promoTawar): ?>
        <div class="glass-badge">
            <span class="badge-pct"><?php echo (int)$promoTawar['diskon_persen']; ?>%</span>
            <span class="badge-text"><strong>Diskon untuk akun baru</strong><small>Daftar dan dapatkan sekarang</small></span>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="container">

    <div class="toolbar">
        <form method="post" action="<?php echo url('lokasi/simpan'); ?>" id="formLokasi" class="pickup-box">
            <div class="pickup-label">
                <span>Ambil di (<?php echo e($_SESSION['jarak_info']); ?>)</span>
                <button type="button" class="gps-btn" onclick="cekGPS()">Cek GPS Terdekat</button>
            </div>
            <input type="hidden" name="jarak_info" id="inputJarak" value="<?php echo e($_SESSION['jarak_info']); ?>" />
            <select name="lokasi_ambil" id="selectKampus" class="pickup-select" onchange="gantiLokasi()">
                <?php foreach ($dataLokasi as $nama => $info): ?>
                <option value="<?php echo e($nama); ?>" data-lat="<?php echo $info['lat']; ?>" data-lon="<?php echo $info['lon']; ?>"<?php if ($_SESSION['lokasi_pilihan'] == $nama) echo ' selected="selected"'; ?>><?php echo e($nama); ?></option>
                <?php endforeach; ?>
            </select>
        </form>
        <input type="text" id="searchMenu" class="search-input" placeholder="Cari kopi, non-kopi, atau roti..." onkeyup="cariMenu()" />
    </div>

    <?php if ($promoSaya): ?>
    <div class="promo" id="promo">
        <div>
            <div class="promo-tag">PROMO UNTUKMU</div>
            <h3><?php echo e($promoSaya['nama_promo']); ?>: diskon <?php echo (int)$promoSaya['diskon_persen']; ?>%</h3>
            <small>Berlaku sampai <?php echo tanggal_jam($promoSaya['berlaku_sampai']); ?> (<?php echo e(sisa_waktu($promoSaya['berlaku_sampai'])); ?>). Harga yang dicoret sudah dipotong otomatis di keranjang.</small>
        </div>
        <a href="#menu" class="btn-white">Pesan Sekarang</a>
    </div>
    <?php elseif (sudah_login() && $jumlahKlaim > 0): ?>
    <div class="promo" id="promo">
        <div>
            <div class="promo-tag">PROMO BARU</div>
            <h3>Ada <?php echo (int)$jumlahKlaim; ?> promo yang bisa kamu klaim</h3>
            <small>Klaim sekarang sebelum kuota atau waktunya habis.</small>
        </div>
        <a href="<?php echo url('promo'); ?>" class="btn-white">Klaim Sekarang</a>
    </div>
    <?php elseif ($promoTawar): ?>
    <div class="promo" id="promo">
        <div>
            <div class="promo-tag">PROMO AKUN BARU</div>
            <h3>Daftar sekarang, dapat diskon <?php echo (int)$promoTawar['diskon_persen']; ?>%</h3>
            <small>Berlaku <?php echo (int)$promoTawar['berlaku_hari']; ?> hari sejak akun dibuat. <?php echo e($promoTawar['deskripsi']); ?></small>
        </div>
        <a href="<?php echo url('register'); ?>" class="btn-white">Daftar Gratis</a>
    </div>
    <?php endif; ?>

    <div class="section-head" id="menu">
        <div class="section-title">Menu Cafe Caritas</div>
        <div class="pills">
            <button type="button" class="pill active" onclick="filterKategori('Semua', this)">Semua</button>
            <button type="button" class="pill" onclick="filterKategori('Basic', this)">Basic</button>
            <button type="button" class="pill" onclick="filterKategori('Signature', this)">Signature</button>
            <button type="button" class="pill" onclick="filterKategori('Flavour', this)">Flavour</button>
            <button type="button" class="pill" onclick="filterKategori('Tea', this)">Tea</button>
            <button type="button" class="pill" onclick="filterKategori('Powder', this)">Powder</button>
            <button type="button" class="pill" onclick="filterKategori('Pastry', this)">Pastry</button>
        </div>
    </div>

    <div class="cards">
        <?php foreach ($signature as $m): ?>
        <?php require 'views/menu/kartu.php'; ?>
        <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:30px;">
        <a href="<?php echo url('menu'); ?>" class="btn-solid">Lihat Selengkapnya</a>
    </div>
</div>

<div class="features">
    <div class="feature">
        <div class="feature-icon">&#128241;</div>
        <strong>Pesan dari mana saja</strong>
        <span>Pilih menu dan atur minumanmu langsung dari kelas.</span>
    </div>
    <div class="feature">
        <div class="feature-icon">&#128179;</div>
        <strong>Bayar pakai QRIS</strong>
        <span>GoPay, OVO, DANA, atau m-Banking. Praktis dan cepat.</span>
    </div>
    <div class="feature">
        <div class="feature-icon">&#9749;</div>
        <strong>Ambil tanpa antre</strong>
        <span>Pantau status pesanan dan ambil saat sudah siap.</span>
    </div>
</div>

