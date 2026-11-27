<div class="white-card table-wrap">
    <div class="judul-aksi"><div class="card-title" style="margin:0;">Daftar Menu</div><a href="<?php echo url('admin/menu/tambah'); ?>" class="btn-sm dark" style="text-decoration:none;">+ Tambah Menu</a></div>
    <table class="tbl">
        <tr>
            <th>Menu</th>
            <th>Grup</th>
            <th>Harga Hot / Ice</th>
            <th>Aksi</th>
        </tr>
        <?php foreach ($daftarMenu as $m): ?>
        <tr>
            <td class="cell-bold"><?php echo e($m['nama_menu']); ?></td>
            <td><?php echo e($m['grup_menu']); ?></td>
            <td>
                <form method="post" action="<?php echo url('admin/menu/update'); ?>" class="inline-form">
                    <input type="hidden" name="menu_id" value="<?php echo (int)$m['id']; ?>" />
                    Hot: <input type="text" name="harga_hot" value="<?php echo e($m['harga_hot']); ?>" class="input-price" />
                    Ice: <input type="text" name="harga_ice" value="<?php echo e($m['harga_ice']); ?>" class="input-price" />
                    <button type="submit" class="btn-sm caramel">Simpan</button>
                </form>
            </td>
            <td>
                <form method="post" action="<?php echo url('admin/menu/hapus'); ?>">
                    <input type="hidden" name="menu_id" value="<?php echo (int)$m['id']; ?>" />
                    <button type="submit" class="btn-link-danger" data-konfirm="Hapus menu ini?">Hapus</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
