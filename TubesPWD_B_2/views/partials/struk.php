<div class="struk">
    <div class="struk-judul">
        <strong>CARITAS CAFE</strong>
        <small><?php echo e($pesanan['lokasi_ambil']); ?></small>
    </div>
    <hr />
    <div class="sum-row"><span>No. antrean</span><strong><?php echo e($pesanan['kode_antrean']); ?></strong></div>
    <div class="sum-row"><span>Tanggal</span><span><?php echo tanggal($pesanan['created_at']); ?> <?php echo date('H:i', strtotime($pesanan['created_at'])); ?></span></div>
    <div class="sum-row"><span>Pelanggan</span><span><?php echo e($pesanan['nama_pemesan']); ?></span></div>
    <div class="sum-row"><span>Tipe</span><span><?php echo e($pesanan['metode_ambil']); ?></span></div>
    <hr />
    <?php if (count($items) > 0): ?>
        <?php foreach ($items as $it): ?>
        <div class="sum-row">
            <span><?php echo $it['qty']; ?>x <?php echo e($it['nama_menu']); ?> (<?php echo e($it['penyajian']); ?>)</span>
            <span><?php echo rupiah($it['qty'] * $it['harga']); ?></span>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach (explode(' | ', $pesanan['rincian_item']) as $baris): ?>
        <div class="sum-row"><span><?php echo e($baris); ?></span></div>
        <?php endforeach; ?>
    <?php endif; ?>
    <hr />
    <div class="sum-row"><span>Subtotal</span><span><?php echo rupiah($pesanan['subtotal']); ?></span></div>
    <?php if ($pesanan['diskon'] > 0): ?>
    <div class="sum-row"><span>Diskon<?php if ($pesanan['promo_nama'] != ''): ?> (<?php echo e($pesanan['promo_nama']); ?>)<?php endif; ?></span><span>-<?php echo rupiah($pesanan['diskon']); ?></span></div>
    <?php endif; ?>
    <div class="sum-row total"><span>Total</span><span><?php echo rupiah($pesanan['total_bayar']); ?></span></div>
    <div class="sum-row"><span>Pembayaran</span><span><?php echo e($pesanan['metode_bayar']); ?></span></div>
    <?php if ($pesanan['uang_diterima'] !== null): ?>
    <div class="sum-row"><span>Tunai</span><span><?php echo rupiah($pesanan['uang_diterima']); ?></span></div>
    <div class="sum-row"><span>Kembalian</span><span><?php echo rupiah($pesanan['uang_diterima'] - $pesanan['total_bayar']); ?></span></div>
    <?php endif; ?>
    <p class="struk-terima">Terima kasih sudah mampir!</p>
</div>
