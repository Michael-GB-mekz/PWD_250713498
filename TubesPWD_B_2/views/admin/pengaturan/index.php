<p class="muted" style="margin-bottom:14px;">Foto dan video di bawah ini berganti otomatis di bagian atas halaman utama. Foto dan video sama-sama tampil selama beberapa detik sesuai kecepatan di bawah (video diputar tanpa suara dan dipotong kalau lebih panjang dari itu). Kalau belum ada isi, halaman utama memakai satu foto bawaan.</p>

<div class="white-card">
    <div class="judul-aksi"><div class="card-title" style="margin:0;">Isi Slideshow (<?php echo count($media); ?>)</div><a href="<?php echo url('admin/pengaturan/tambah'); ?>" class="btn-sm dark" style="text-decoration:none;">+ Tambah Foto atau Video</a></div>
    <?php if (count($media) == 0): ?>
        <p class="muted">Belum ada foto atau video. Tambahkan yang pertama di bawah.</p>
    <?php else: ?>
    <div class="hero-daftar">
        <?php foreach ($media as $i => $m): ?>
        <div class="hero-item">
            <?php if ($m['jenis'] == 'video'): ?>
                <video src="<?php echo e($m['sumber']); ?>" class="pratinjau-hero" muted="muted" preload="metadata" controls="controls"></video>
            <?php else: ?>
                <img src="<?php echo e($m['sumber']); ?>" alt="Foto <?php echo $i + 1; ?>" class="pratinjau-hero" />
            <?php endif; ?>
            <div class="hero-item-bawah">
                <span class="badge <?php echo $m['jenis'] == 'video' ? 'badge-biru' : 'badge-abu'; ?>"><?php echo $i + 1; ?>. <?php echo $m['jenis'] == 'video' ? 'Video' : 'Foto'; ?></span>
                <form method="post" action="<?php echo url('admin/pengaturan/media-hapus'); ?>">
                    <input type="hidden" name="id" value="<?php echo (int)$m['id']; ?>" />
                    <button type="submit" class="btn-link-danger" data-konfirm="Hapus dari slideshow?">Hapus</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<div class="white-card form-sempit" style="max-width:640px;">
    <div class="card-title">Kecepatan Ganti Foto dan Video</div>
    <form method="post" action="<?php echo url('admin/pengaturan/detik'); ?>" class="inline-form">
        <span class="opt-label" style="margin:0;">Tiap foto atau video tampil selama</span>
        <input type="text" name="detik" value="<?php echo $detik; ?>" class="input-price" style="width:70px;" />
        <span class="muted">detik (2 sampai 15)</span>
        <button type="submit" class="btn-sm dark">Simpan</button>
    </form>
</div>
