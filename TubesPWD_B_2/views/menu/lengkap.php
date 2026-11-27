<div class="page-head">
    <div class="container">
        <a href="<?php echo url('home'); ?>" class="back-link">&larr; Kembali ke beranda</a>
        <h1>Menu Lengkap</h1>
        <p>Semua kopi, minuman non-kopi, dan pastry Caritas Cafe. Klik kartu untuk melihat detail dan memesan.</p>
    </div>
</div>

<div class="container">
    <div class="toolbar toolbar-flat">
        <input type="text" id="searchMenu" class="search-input" placeholder="Cari kopi, non-kopi, atau roti..." onkeyup="cariMenu()" />
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

    <?php foreach ($menuPerGrup as $grup => $daftarMenu): ?>
    <div class="group-section">
        <div class="group-title"><?php echo e($grup); ?></div>
        <div class="cards">
            <?php foreach ($daftarMenu as $m): ?>
            <?php require 'views/menu/kartu.php'; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
