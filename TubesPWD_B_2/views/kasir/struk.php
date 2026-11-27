<div class="struk-kasir">
    <?php require 'views/partials/struk.php'; ?>
</div>

<div class="struk-aksi no-print">
    <button type="button" class="btn-sm dark" onclick="window.print()">Cetak Struk</button>
    <a href="<?php echo url('kasir'); ?>" class="btn-sm caramel" style="text-decoration:none;">Transaksi Baru</a>
    <a href="<?php echo url('kasir/riwayat'); ?>" class="btn-link-danger" style="color:#412D15;">Lihat riwayat</a>
</div>
