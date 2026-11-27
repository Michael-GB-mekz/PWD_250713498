<p style="margin-bottom:14px;"><a href="<?php echo url('admin/pengaturan'); ?>" class="back-link">&larr; Kembali ke Tampilan Toko</a></p>

<div class="white-card form-sempit">
    <form method="post" action="<?php echo url('admin/pengaturan/media-tambah'); ?>" enctype="multipart/form-data">
        <span class="opt-label">Unggah file (foto JPG, PNG, WEBP maks. 3 MB, video MP4 atau WEBM maks. 30 MB)</span>
        <input type="file" name="file_media" accept="image/jpeg,image/png,image/webp,video/mp4,video/webm" class="input" />

        <span class="opt-label">Atau tempel alamat file (berakhiran .jpg, .png, .webp, .mp4, atau .webm)</span>
        <input type="text" name="url_media" placeholder="https://..." class="input" />

        <p class="muted" style="margin-bottom:16px;">Untuk video, pakai resolusi 720p dan durasi 5 sampai 15 detik supaya cepat dimuat. Video selalu diputar tanpa suara.</p>

        <div class="btn-row">
            <button type="submit" class="btn-full">Tambah ke Slideshow</button>
            <a href="<?php echo url('admin/pengaturan'); ?>" class="btn-full light">Batal</a>
        </div>
    </form>
</div>
