<div class="white-card table-wrap">
    <div class="card-title">Antrean Pesanan</div>
    <table class="tbl">
        <tr>
            <th>Antrean</th>
            <th>Pemesan</th>
            <th>Pesanan</th>
            <th>Titik Ambil</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
        <?php foreach ($pesanan as $o): ?>
        <tr>
            <td class="cell-queue">
                <?php echo e($o['kode_antrean']); ?><br />
                <span class="badge <?php echo $o['sumber'] == 'kasir' ? 'badge-biru' : 'badge-hijau'; ?>"><?php echo e($o['sumber']); ?></span>
            </td>
            <td><?php echo e($o['nama_pemesan']); ?><br /><small><?php echo e($o['no_wa']); ?></small></td>
            <td>
                <?php if (count($itemPerPesanan[$o['id']]) > 0): ?>
                    <?php foreach ($itemPerPesanan[$o['id']] as $it): ?>
                        <?php echo $it['qty']; ?>x <?php echo e($it['nama_menu']); ?> <small class="muted">(<?php echo e($it['penyajian']); ?>)</small><br />
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php echo e($o['rincian_item']); ?>
                <?php endif; ?>
                <?php if ($o['catatan'] != ''): ?><small class="muted">Catatan: <?php echo e($o['catatan']); ?></small><?php endif; ?>
            </td>
            <td><?php echo e($o['lokasi_ambil']); ?><br /><small>(<?php echo e($o['jarak_info']); ?>)</small></td>
            <td class="cell-bold"><?php echo rupiah($o['total_bayar']); ?><br /><small class="muted"><?php echo e($o['metode_bayar']); ?></small></td>
            <td>
                <form method="post" action="<?php echo url('admin/pesanan/status'); ?>" class="inline-form">
                    <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>" />
                    <select name="status" class="select-sm">
                        <?php for ($i = 0; $i < count($daftarStatus); $i++): ?>
                        <option value="<?php echo e($daftarStatus[$i]); ?>"<?php if ($o['status'] == $daftarStatus[$i]) echo ' selected="selected"'; ?>><?php echo ($i + 1) . '. ' . $daftarStatus[$i]; ?></option>
                        <?php endfor; ?>
                    </select>
                    <button type="submit" class="btn-sm dark">Update</button>
                </form>
                <?php if (role_adalah('admin')): ?>
                <form method="post" action="<?php echo url('admin/pesanan/hapus'); ?>" style="margin-top:6px;">
                    <input type="hidden" name="order_id" value="<?php echo (int)$o['id']; ?>" />
                    <button type="submit" class="btn-link-danger" data-konfirm="Hapus pesanan ini?">Hapus</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
