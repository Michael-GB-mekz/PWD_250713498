<p class="muted" style="margin-bottom:14px;">Promo <strong>bisa diklaim</strong> muncul di halaman Promo dan diklaim sendiri oleh pelanggan (bisa dibatasi kuota dan batas waktu klaim). Promo <strong>otomatis</strong> langsung diterima setiap akun baru. Tenggat pemakaian dihitung sejak promo diterima atau diklaim. Perubahan persen diskon dan status aktif langsung berlaku untuk semua pemilik promo, sedangkan masa berlaku hanya untuk yang menerima sesudahnya.</p>

<div class="white-card table-wrap">
    <div class="judul-aksi"><div class="card-title" style="margin:0;">Daftar Promo</div><a href="<?php echo url('admin/promo/tambah'); ?>" class="btn-sm dark" style="text-decoration:none;">+ Tambah Promo</a></div>
    <table class="tbl">
        <tr>
            <th>Promo</th>
            <th>Jenis</th>
            <th>Diskon</th>
            <th>Masa berlaku</th>
            <th>Batas pakai</th>
            <th>Kuota klaim</th>
            <th>Klaim sampai</th>
            <th>Status</th>
            <th>Diterima</th>
            <th>Terpakai</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftar as $p): ?>
        <tr>
            <td><strong><?php echo e($p['nama_promo']); ?></strong><br /><small class="muted"><?php echo e($p['deskripsi']); ?></small></td>
            <td><span class="badge <?php echo $p['jenis'] == 'klaim' ? 'badge-biru' : 'badge-hijau'; ?>"><?php echo $p['jenis'] == 'klaim' ? 'Klaim' : 'Otomatis'; ?></span></td>
            <td class="cell-bold"><?php echo (int)$p['diskon_persen']; ?>%</td>
            <td><?php echo (int)$p['berlaku_hari']; ?> hari</td>
            <td><?php echo $p['batas_pakai'] == 0 ? 'Bebas' : (int)$p['batas_pakai'] . 'x'; ?></td>
            <td>
                <?php if ($p['jenis'] == 'klaim'): ?>
                    <?php echo $p['kuota'] == 0 ? 'Bebas' : (int)$p['penerima'] . ' / ' . (int)$p['kuota']; ?>
                <?php else: ?><span class="muted">-</span><?php endif; ?>
            </td>
            <td>
                <?php if ($p['jenis'] == 'klaim' && $p['klaim_sampai'] != null): ?><?php echo tanggal_jam($p['klaim_sampai']); ?>
                <?php else: ?><span class="muted">-</span><?php endif; ?>
            </td>
            <td><span class="badge <?php echo $p['aktif'] ? 'badge-hijau' : 'badge-abu'; ?>"><?php echo $p['aktif'] ? 'Aktif' : 'Nonaktif'; ?></span></td>
            <td><?php echo (int)$p['penerima']; ?> akun</td>
            <td><?php echo (int)$p['terpakai']; ?>x</td>
            <td>
                <a href="<?php echo url('admin/promo/edit', array('id' => $p['id'])); ?>" class="btn-sm caramel" style="text-decoration:none;">Ubah</a>
                <form method="post" action="<?php echo url('admin/promo/berikan'); ?>" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>" />
                    <button type="submit" class="btn-link-danger" style="color:#412D15;" data-konfirm="Berikan promo ini ke semua akun yang belum memilikinya? Tenggat dihitung mulai sekarang.">Berikan ke semua akun</button>
                </form>
                <form method="post" action="<?php echo url('admin/promo/hapus'); ?>" style="display:inline;">
                    <input type="hidden" name="id" value="<?php echo (int)$p['id']; ?>" />
                    <button type="submit" class="btn-link-danger" data-konfirm="Hapus promo ini? Promo juga hilang dari semua akun pemiliknya.">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php if (count($daftar) == 0): ?><p class="muted">Belum ada promo.</p><?php endif; ?>
</div>
