<?php
global $dataLokasi;
$subhalaman = 'pesanan';
?>
<div class="akun">
<?php require 'views/profile/_menu.php'; ?>

<div class="akun-isi">
<h2 style="margin-bottom:14px;">Pesanan Saya</h2>

<?php if (count($riwayat) == 0): ?>
<div class="white-card" style="text-align:center; padding:34px 18px;">
    <strong style="font-size:17px;">Belum ada pesanan</strong>
    <p class="muted" style="margin:8px 0 18px;">Yuk pesan kopi favoritmu sekarang.</p>
    <a href="<?php echo url('home'); ?>" class="btn-solid">Pesan Kopi Sekarang</a>
</div>
<?php else: ?>
<?php $terbaru = $riwayat[0]; ?>

<div class="queue-card">
    <div>Nomor Antrean</div>
    <div class="queue-code"><?php echo e($terbaru['kode_antrean']); ?></div>
    <strong>Pembayaran: <?php echo e($terbaru['metode_bayar']); ?></strong>
</div>

<div class="white-card">
    <?php
    $langkah = array(
        array('Pesanan diterima', 'Pukul ' . date('H:i', strtotime($terbaru['created_at']))),
        array('Sedang dibuat barista', 'Barista sedang menyiapkan pesananmu'),
        array('Siap diambil', 'Di ' . $terbaru['lokasi_ambil']),
        array('Selesai', 'Terima kasih sudah memesan')
    );
    $posisi = 0;
    for ($i = 0; $i < count($langkah); $i++) {
        if ($langkah[$i][0] == $terbaru['status']) {
            $posisi = $i;
        }
    }
    for ($i = 0; $i < count($langkah); $i++) {
        $kelas = '';
        $isi = $i + 1;
        if ($i <= $posisi) {
            $kelas = ' done';
            $isi = '&#10003;';
        } elseif ($i == $posisi + 1) {
            $kelas = ' active';
        }
    ?>
    <div class="step">
        <div class="step-dot<?php echo $kelas; ?>"><?php echo $isi; ?></div>
        <div>
            <strong><?php echo $langkah[$i][0]; ?></strong><br />
            <small><?php echo e($langkah[$i][1]); ?></small>
        </div>
    </div>
    <?php } ?>
</div>

<div class="white-card">
    <div class="small-title">RINGKASAN</div>
    <p><?php echo e($terbaru['rincian_item']); ?></p>
    <div class="sum-row total">
        <span>Total</span>
        <span><?php echo rupiah($terbaru['total_bayar']); ?></span>
    </div>
    <?php if ($terbaru['diskon'] > 0): ?>
    <div class="hemat-info hemat-besar">Kamu hemat <?php echo rupiah($terbaru['diskon']); ?><?php if ($terbaru['promo_nama'] != ''): ?> dengan <?php echo e($terbaru['promo_nama']); ?><?php endif; ?>.</div>
    <?php endif; ?>
    <a href="<?php echo url('pesanan/struk', array('id' => $terbaru['id'])); ?>" class="btn-full light" style="margin-top:12px;">Lihat dan Cetak Struk</a>
</div>

<?php
$kataPeta = $terbaru['lokasi_ambil'] . ' Yogyakarta';
if (isset($dataLokasi[$terbaru['lokasi_ambil']])) {
    $kataPeta = $dataLokasi[$terbaru['lokasi_ambil']]['peta'];
}
?>
<button type="button" class="btn-full caramel" onclick="tampilkanPeta('<?php echo e(addslashes($kataPeta)); ?>')">Lihat Lokasi Cafe Caritas</button>
<div id="boxPeta" class="white-card" style="display:none; margin-top:16px;">
    <strong><?php echo e($terbaru['lokasi_ambil']); ?></strong>
    <p class="muted">Buka setiap hari kerja, 08.00 - 16.30 WIB</p>
    <iframe id="iframePeta" title="Lokasi Caritas" style="width:100%; height:200px; border:0; border-radius:12px;"></iframe>
</div>

<?php if ($terbaru['status'] == 'Pesanan diterima'): ?>
<div class="white-card" style="margin-top:16px;">
    <div class="card-title">Ubah titik ambil atau catatan</div>
    <form method="post" action="<?php echo url('pesanan/ubah'); ?>">
        <input type="hidden" name="order_id" value="<?php echo (int)$terbaru['id']; ?>" />
        <select name="lokasi_ambil" class="input">
            <?php foreach ($dataLokasi as $nama => $info): ?>
            <option value="<?php echo e($nama); ?>"<?php if ($terbaru['lokasi_ambil'] == $nama) echo ' selected="selected"'; ?>><?php echo e($nama); ?></option>
            <?php endforeach; ?>
        </select>
        <input type="text" name="catatan" value="<?php echo e($terbaru['catatan']); ?>" placeholder="Catatan tambahan" class="input" />
        <button type="submit" class="btn-full">Simpan</button>
    </form>
    <form method="post" action="<?php echo url('pesanan/batal'); ?>" style="margin-top:10px;">
        <input type="hidden" name="order_id" value="<?php echo (int)$terbaru['id']; ?>" />
        <button type="submit" class="btn-full danger" data-konfirm="Batalkan pesanan ini?">Batalkan Pesanan</button>
    </form>
</div>
<?php endif; ?>

<?php if (count($riwayat) > 1): ?>
<div class="white-card" style="margin-top:16px;">
    <div class="card-title">Riwayat pesanan</div>
    <?php for ($i = 1; $i < count($riwayat); $i++): ?>
    <div class="cart-line">
        <div>
            <strong><?php echo e($riwayat[$i]['kode_antrean']); ?></strong>
            <small><?php echo tanggal($riwayat[$i]['created_at']); ?> - <?php echo e($riwayat[$i]['rincian_item']); ?></small>
        </div>
        <div style="text-align:right;">
            <div class="cell-bold"><?php echo rupiah($riwayat[$i]['total_bayar']); ?></div>
            <a href="<?php echo url('pesanan/struk', array('id' => $riwayat[$i]['id'])); ?>" class="btn-link-danger" style="color:#412D15;">Struk</a>
        </div>
    </div>
    <?php endfor; ?>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
</div>
