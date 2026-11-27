<?php
$isiKeranjang = keranjang_semua();
$subtotalKeranjang = keranjang_subtotal();
$promoPilihan = array();
if (sudah_login() && !adalah_staf()) {
    $promoPilihan = promos_aktif_semua_user((int)$_SESSION['user_id']);
}
$persenAwal = count($promoPilihan) > 0 ? (int)$promoPilihan[0]['diskon_persen'] : 0;
$diskonAwal = keranjang_diskon($subtotalKeranjang, $persenAwal);
$totalAwal = $subtotalKeranjang - $diskonAwal;

$adaMinuman = false;
$adaRoti = false;
foreach ($isiKeranjang as $item) {
    if (adalah_roti($item['grup'])) {
        $adaRoti = true;
    } else {
        $adaMinuman = true;
    }
}
$saran = array();
if ($adaMinuman && !$adaRoti) {
    $saran = menus_rekomendasi(true, 0, 3);
} elseif ($adaRoti && !$adaMinuman) {
    $saran = menus_rekomendasi(false, 0, 3);
}

$langkahBayar = (isset($_GET['langkah']) && $_GET['langkah'] == 'bayar');
?>
<div class="modal<?php if ($bukaKeranjang) echo ' show'; ?>" id="modalKeranjang">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="tutupModal()" aria-label="Tutup"></button>

        <?php if (count($isiKeranjang) == 0): ?>
            <div class="modal-title">Keranjang</div>
            <p class="muted" style="margin:10px 0 16px;">Keranjangmu masih kosong.</p>
            <button type="button" class="btn-full" onclick="tutupModal()">Pilih Menu</button>
        <?php else: ?>
        <form method="post" action="<?php echo url('checkout/bayar'); ?>">
            <input type="hidden" name="metode_ambil" value="Pickup" />

            <div id="stepKeranjang"<?php if ($langkahBayar) echo ' style="display:none;"'; ?>>
                <div class="modal-title" style="margin-bottom:12px;">Keranjang</div>

                <?php if ($flash && $bukaKeranjang): ?>
                <div class="alert alert-<?php echo e($flash['jenis']); ?>"><?php echo e($flash['pesan']); ?></div>
                <?php endif; ?>

                <div class="white-card">
                    <?php foreach ($isiKeranjang as $no => $item): ?>
                    <div class="cart-line">
                        <div>
                            <strong><?php echo e($item['nama']); ?></strong>
                            <small><?php echo e($item['grup']); ?> - <?php echo e($item['penyajian']); ?></small>
                            <span class="price"><?php echo harga_html($item['harga']); ?></span>
                        </div>
                        <div class="cart-qty">
                            <a href="<?php echo url('keranjang/kurang-qty', array('i' => $no)); ?>">-</a>
                            <input type="text" class="qty-ketik" value="<?php echo (int)$item['qty']; ?>" maxlength="3" onchange="setQtyKeranjang(this, '<?php echo url('keranjang/set-qty', array('i' => $no)); ?>')" onkeydown="qtyEnter(event, this)" />
                            <a href="<?php echo url('keranjang/tambah-qty', array('i' => $no)); ?>">+</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($saran) > 0): ?>
                <div class="white-card">
                    <span class="small-title"><?php echo $adaRoti ? 'TEMANI DENGAN MINUMAN' : 'LENGKAPI DENGAN PASTRY'; ?></span>
                    <div class="rekomendasi-daftar">
                        <?php foreach ($saran as $s): ?>
                        <a href="<?php echo url('menu/detail', array('id' => $s['id'])); ?>" class="rekomendasi-item">
                            <strong><?php echo e($s['nama_menu']); ?></strong>
                            <span><?php echo harga_kartu_html($s); ?></span>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="white-card">
                    <div class="sum-row"><span>Subtotal</span><span><?php echo rupiah($subtotalKeranjang); ?></span></div>
                    <div class="sum-row total"><span>Total</span><span><?php echo rupiah($subtotalKeranjang); ?></span></div>
                    <?php if (count($promoPilihan) > 0): ?>
                    <div class="hemat-info hemat-besar">Kamu punya <?php echo count($promoPilihan); ?> promo. Pilih promonya di langkah pembayaran.</div>
                    <?php endif; ?>
                </div>

                <button type="button" class="btn-full" onclick="keStepPembayaran()">Lanjut ke Pembayaran</button>
            </div>

            <div id="stepPembayaran"<?php if (!$langkahBayar) echo ' style="display:none;"'; ?>>
                <div class="modal-title" style="margin-bottom:12px;">Pembayaran</div>

                <?php if ($flash && $bukaKeranjang): ?>
                <div class="alert alert-<?php echo e($flash['jenis']); ?>"><?php echo e($flash['pesan']); ?></div>
                <?php endif; ?>

                <?php if (!sudah_login()): ?>
                <div class="alert alert-error">Kamu perlu masuk dulu sebelum membayar. <a href="<?php echo url('login'); ?>">Masuk di sini</a></div>
                <?php endif; ?>

                <div class="white-card">
                    <span class="small-title">WAKTU AMBIL</span>
                    <select name="waktu_ambil" class="input" style="margin-bottom:0;">
                        <option value="Sekarang">Sekarang</option>
                        <option value="Jadwalkan">Jadwalkan (15 menit lagi)</option>
                    </select>
                </div>

                <?php if (count($promoPilihan) > 0): ?>
                <div class="white-card">
                    <span class="small-title">PROMO</span>
                    <label class="pay-option"><input type="radio" name="promo_id" value="0" data-persen="0" onclick="hitungTotalBayar()" /> Tanpa promo</label>
                    <?php foreach ($promoPilihan as $i => $p): ?>
                    <label class="pay-option">
                        <input type="radio" name="promo_id" value="<?php echo (int)$p['promo_id']; ?>" data-persen="<?php echo (int)$p['diskon_persen']; ?>"<?php if ($i == 0) echo ' checked="checked"'; ?> onclick="hitungTotalBayar()" />
                        <span class="opsi-teks"><?php echo e($p['nama_promo']); ?> (<?php echo (int)$p['diskon_persen']; ?>%)<small>Hemat <?php echo rupiah(keranjang_diskon($subtotalKeranjang, (int)$p['diskon_persen'])); ?>. Berlaku <?php echo e(sisa_waktu($p['berlaku_sampai'])); ?>.</small></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="white-card">
                    <span class="small-title">METODE PEMBAYARAN</span>
                    <label class="pay-option"><input type="radio" name="metode_bayar" value="QRIS" checked="checked" onclick="tampilKomitmen()" /> QRIS (GoPay, OVO, DANA, m-Banking)</label>
                    <label class="pay-option"><input type="radio" name="metode_bayar" value="Transfer Virtual Account" onclick="tampilKomitmen()" /> Transfer Virtual Account</label>
                    <label class="pay-option"><input type="radio" name="metode_bayar" value="Bayar di Kasir" onclick="tampilKomitmen()" /> Bayar di Kasir (tunai)</label>

                    <div class="komitmen-box" id="boxKomitmen" style="display:none;">
                        <label>
                            <input type="checkbox" name="komitmen" value="1" />
                            Saya berkomitmen datang ke kasir dan membayar tunai dalam 10 menit setelah memesan, sebelum pesanan dibuat.
                        </label>
                        <small>Pesanan baru dibuat barista setelah dibayar. Kalau tidak datang dalam 10 menit, pesanan dibatalkan, dan akun yang sering melakukannya bisa dibatasi.</small>
                    </div>
                </div>

                <div id="ringkasanBayar" data-subtotal="<?php echo (int)$subtotalKeranjang; ?>" style="margin-bottom:12px;">
                    <div class="sum-row"><span>Subtotal</span><span><?php echo rupiah($subtotalKeranjang); ?></span></div>
                    <div class="sum-row" id="barisDiskon" style="color:#16a34a;<?php if ($diskonAwal == 0) echo ' display:none;'; ?>"><span>Diskon</span><span id="txtDiskon">-<?php echo rupiah($diskonAwal); ?></span></div>
                    <div class="sum-row total">
                        <span>Total Bayar</span>
                        <span id="txtTotal2"><?php echo rupiah($totalAwal); ?></span>
                    </div>
                </div>
                <button type="submit" class="btn-full caramel">Bayar Sekarang</button>
                <button type="button" class="btn-full light" style="margin-top:8px;" onclick="kembaliKeKeranjang()">Kembali</button>
            </div>

        </form>
        <?php endif; ?>
    </div>
</div>
