<p style="margin-bottom:14px;"><a href="<?php echo url('admin/menu'); ?>" class="back-link">&larr; Kembali ke daftar menu</a></p>

<div class="white-card form-sempit">
    <form method="post" action="<?php echo url('admin/menu/tambah'); ?>">
        <span class="opt-label">Nama menu</span>
        <input type="text" name="nama_menu" value="<?php echo e($lama['nama_menu']); ?>" class="input" maxlength="100" />

        <span class="opt-label">Grup</span>
        <select name="grup_menu" class="input">
            <option value="">Pilih grup</option>
            <?php foreach ($daftarGrup as $g): ?>
            <option value="<?php echo e($g); ?>"<?php if ($lama['grup_menu'] == $g) echo ' selected="selected"'; ?>><?php echo e($g); ?></option>
            <?php endforeach; ?>
        </select>

        <span class="opt-label">Tag filter</span>
        <select name="filter_tag" class="input">
            <option value="">Pilih tag</option>
            <?php foreach ($daftarTag as $t): ?>
            <option value="<?php echo e($t); ?>"<?php if ($lama['filter_tag'] == $t) echo ' selected="selected"'; ?>><?php echo e($t); ?></option>
            <?php endforeach; ?>
        </select>

        <span class="opt-label">Harga Hot (kosongkan kalau tidak ada)</span>
        <input type="text" name="harga_hot" value="<?php echo e($lama['harga_hot']); ?>" class="input" placeholder="Contoh: 20000" />

        <span class="opt-label">Harga Ice (kosongkan kalau tidak ada)</span>
        <input type="text" name="harga_ice" value="<?php echo e($lama['harga_ice']); ?>" class="input" placeholder="Contoh: 22000" />

        <span class="opt-label">Deskripsi (opsional)</span>
        <input type="text" name="deskripsi" value="<?php echo e($lama['deskripsi']); ?>" class="input" maxlength="255" />

        <span class="opt-label">Alamat foto (opsional, kosong memakai foto bawaan)</span>
        <input type="text" name="gambar_url" value="<?php echo e($lama['gambar_url']); ?>" class="input" placeholder="https://..." />

        <label class="check-label" style="margin-bottom:16px;"><input type="checkbox" name="is_signature"<?php if ($lama['is_signature']) echo ' checked="checked"'; ?> /> Tampilkan sebagai menu Signature di beranda</label>

        <div class="btn-row">
            <button type="submit" class="btn-full">Simpan Menu</button>
            <a href="<?php echo url('admin/menu'); ?>" class="btn-full light">Batal</a>
        </div>
    </form>
</div>
