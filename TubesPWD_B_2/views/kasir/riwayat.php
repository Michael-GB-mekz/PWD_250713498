<div class="white-card">
    <form method="get" action="index.php" class="inline-form">
        <input type="hidden" name="r" value="kasir/riwayat" />
        <span class="opt-label" style="margin:0;">Dari</span>
        <input type="date" name="dari" value="<?php echo e($dari); ?>" class="select-sm" />
        <span class="opt-label" style="margin:0;">Sampai</span>
        <input type="date" name="sampai" value="<?php echo e($sampai); ?>" class="select-sm" />
        <button type="submit" class="btn-sm dark">Tampilkan</button>
    </form>
</div>

<div class="stats">
    <div class="stat-card">
        <span>Jumlah transaksi</span>
        <strong><?php echo count($transaksi); ?></strong>
    </div>
    <div class="stat-card">
        <span>Total penjualan</span>
        <strong><?php echo rupiah($total); ?></strong>
    </div>
</div>

<div class="white-card table-wrap">
    <div class="card-title">Daftar transaksi</div>
    <?php if (count($transaksi) == 0): ?>
        <p class="muted">Belum ada transaksi pada rentang tanggal ini.</p>
    <?php else: ?>
    <table class="tbl">
        <tr>
            <th>Antrean</th>
            <th>Waktu</th>
            <th>Pelanggan</th>
            <th>Pesanan</th>
            <th>Pembayaran</th>
            <th>Total</th>
            <?php if (role_adalah('admin')): ?><th>Kasir</th><?php endif; ?>
            <th></th>
        </tr>
        <?php foreach ($transaksi as $t): ?>
        <tr>
            <td class="cell-queue"><?php echo e($t['kode_antrean']); ?></td>
            <td><?php echo tanggal($t['created_at']); ?><br /><small><?php echo date('H:i', strtotime($t['created_at'])); ?></small></td>
            <td><?php echo e($t['nama_pemesan']); ?><br /><small><?php echo e($t['metode_ambil']); ?></small></td>
            <td><?php echo e($t['rincian_item']); ?></td>
            <td><?php echo e($t['metode_bayar']); ?></td>
            <td class="cell-bold"><?php echo rupiah($t['total_bayar']); ?></td>
            <?php if (role_adalah('admin')): ?><td><?php echo e(nama_staf($t['dibuat_oleh'])); ?></td><?php endif; ?>
            <td><a href="<?php echo url('kasir/struk', array('id' => $t['id'])); ?>" class="btn-link-danger" style="color:#412D15;">Struk</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
