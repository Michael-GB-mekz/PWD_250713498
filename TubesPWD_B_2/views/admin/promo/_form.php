<form method="post" action="<?php echo $aksiForm; ?>">
    <?php if ($idPromo != ''): ?><input type="hidden" name="id" value="<?php echo (int)$idPromo; ?>" /><?php endif; ?>

    <span class="opt-label">Nama promo</span>
    <input type="text" name="nama_promo" value="<?php echo e($nilai['nama_promo']); ?>" class="input" maxlength="100" />

    <span class="opt-label">Deskripsi</span>
    <input type="text" name="deskripsi" value="<?php echo e($nilai['deskripsi']); ?>" class="input" maxlength="255" />

    <span class="opt-label">Jenis promo</span>
    <select name="jenis" class="input">
        <option value="klaim"<?php if ($nilai['jenis'] == 'klaim') echo ' selected="selected"'; ?>>Bisa diklaim pelanggan di halaman Promo</option>
        <option value="otomatis"<?php if ($nilai['jenis'] == 'otomatis') echo ' selected="selected"'; ?>>Otomatis untuk setiap akun baru</option>
    </select>

    <span class="opt-label">Diskon (%)</span>
    <input type="text" name="diskon_persen" value="<?php echo e($nilai['diskon_persen']); ?>" class="input" />

    <span class="opt-label">Masa berlaku sejak promo diterima atau diklaim (hari)</span>
    <input type="text" name="berlaku_hari" value="<?php echo e($nilai['berlaku_hari']); ?>" class="input" />

    <span class="opt-label">Batas pakai per akun (0 = tanpa batas)</span>
    <input type="text" name="batas_pakai" value="<?php echo e($nilai['batas_pakai']); ?>" class="input" />

    <span class="opt-label">Kuota klaim (0 = tanpa batas, hanya untuk promo klaim)</span>
    <input type="text" name="kuota" value="<?php echo e($nilai['kuota']); ?>" class="input" />

    <span class="opt-label">Klaim dibuka sampai (kosongkan kalau tanpa batas, hanya untuk promo klaim)</span>
    <input type="datetime-local" name="klaim_sampai" value="<?php echo e($nilai['klaim_sampai']); ?>" class="input" />

    <label class="check-label" style="margin-bottom:16px;"><input type="checkbox" name="aktif"<?php if ($nilai['aktif']) echo ' checked="checked"'; ?> /> Promo aktif</label>

    <div class="btn-row">
        <button type="submit" class="btn-full"><?php echo e($teksTombol); ?></button>
        <a href="<?php echo url('admin/promo'); ?>" class="btn-full light">Batal</a>
    </div>
</form>
