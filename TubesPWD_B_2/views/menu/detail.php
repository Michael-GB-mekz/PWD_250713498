<?php
$menuRoti = adalah_roti($menu['grup_menu']);
?>
<div class="container">
    <a href="<?php echo url('menu'); ?>" class="back-link" style="display:inline-block; margin-top:24px;">&larr; Kembali ke menu lengkap</a>

    <div class="detail">
        <img src="<?php echo e($menu['gambar_url']); ?>" alt="<?php echo e($menu['nama_menu']); ?>" class="detail-img" />

        <div class="detail-info">
            <span class="hero-tag"><?php echo e($menu['grup_menu']); ?></span>
            <h1><?php echo e($menu['nama_menu']); ?></h1>
            <?php if ($terjual > 0): ?>
            <div class="bukti-sosial"><?php echo menu_terlaris($menu['id']) ? 'Terlaris. ' : ''; ?><?php echo $terjual; ?> porsi sudah dipesan pelanggan Caritas.</div>
            <?php endif; ?>
            <?php if ($menu['deskripsi'] != ''): ?>
            <p class="detail-desc"><?php echo e($menu['deskripsi']); ?></p>
            <?php endif; ?>
            <div class="modal-price" id="mHarga" style="font-size:28px;"><?php echo harga_kartu_html($menu); ?></div>

            <form method="post" action="<?php echo url('keranjang/tambah'); ?>" id="formDetail"
                data-grup="<?php echo e($menu['grup_menu']); ?>"
                data-diskon="0"
                data-hot="<?php echo (int)$menu['harga_hot']; ?>"
                data-ice="<?php echo (int)$menu['harga_ice']; ?>">
                <input type="hidden" name="menu_id" value="<?php echo (int)$menu['id']; ?>" />
                <input type="hidden" name="penyajian" id="inPenyajian" value="<?php echo $menuRoti ? 'Regular' : 'Ice'; ?>" />
                <input type="hidden" id="inHarga" value="0" />
                <input type="hidden" name="gula" id="inGula" value="Normal" />
                <input type="hidden" name="es" id="inEs" value="Normal" />

                <div id="boxPenyajian">
                    <span class="opt-label">Penyajian</span>
                    <div class="chips">
                        <button type="button" class="chip" id="btnHot" onclick="pilihPenyajian('Hot')">Hot</button>
                        <button type="button" class="chip" id="btnIce" onclick="pilihPenyajian('Ice')">Ice</button>
                    </div>
                    <div id="infoHemat" class="hemat-info"></div>
                </div>

                <div id="boxGulaEs">
                    <span class="opt-label">Gula</span>
                    <div class="chips">
                        <button type="button" class="chip" onclick="pilihChip(this, 'inGula', 'Less')">Less</button>
                        <button type="button" class="chip selected" onclick="pilihChip(this, 'inGula', 'Normal')">Normal</button>
                        <button type="button" class="chip" onclick="pilihChip(this, 'inGula', 'Extra')">Extra</button>
                    </div>
                    <div id="boxEs">
                        <span class="opt-label">Es</span>
                        <div class="chips">
                            <button type="button" class="chip" onclick="pilihChip(this, 'inEs', 'Less Ice')">Less Ice</button>
                            <button type="button" class="chip selected" onclick="pilihChip(this, 'inEs', 'Normal')">Normal</button>
                            <button type="button" class="chip" onclick="pilihChip(this, 'inEs', 'Extra Ice')">Extra Ice</button>
                        </div>
                    </div>
                </div>

                <span class="opt-label">Catatan</span>
                <input type="text" name="catatan" class="input" placeholder="Contoh: tanpa sedotan" />

                <span class="opt-label">Jumlah</span>
                <div class="qty-box" style="display:inline-flex;">
                    <button type="button" onclick="ubahQty(-1)">-</button>
                    <input type="text" name="qty" id="inQty" value="1" maxlength="3" onkeyup="cekQty()" onchange="cekQty()" />
                    <button type="button" onclick="ubahQty(1)">+</button>
                </div>

                <div class="detail-btns">
                    <?php if (sudah_login()): ?>
                    <button type="submit" name="aksi" value="keranjang" id="btnTambah" data-label="Masukkan Keranjang" class="btn-full light">Masukkan Keranjang</button>
                    <button type="submit" name="aksi" value="beli" class="btn-full caramel">Beli Sekarang</button>
                    <?php else: ?>
                    <button type="button" id="btnTambah" data-label="Masukkan Keranjang" class="btn-full light" data-konfirm="Kamu harus login dulu untuk memesan. Masuk ke akunmu sekarang?" data-judul="Login dulu ya" data-tombol="Login" data-tuju="<?php echo url('login'); ?>">Masukkan Keranjang</button>
                    <button type="button" class="btn-full caramel" data-konfirm="Kamu harus login dulu untuk memesan. Masuk ke akunmu sekarang?" data-judul="Login dulu ya" data-tombol="Login" data-tuju="<?php echo url('login'); ?>">Beli Sekarang</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <?php if (count($rekomendasi) > 0): ?>
    <div class="rekomendasi">
        <div class="section-title" style="font-size:20px;"><?php echo $menuRoti ? 'Temani dengan minuman' : 'Pas dipasangkan dengan'; ?></div>
        <div class="rekomendasi-daftar">
            <?php foreach ($rekomendasi as $r): ?>
            <a href="<?php echo url('menu/detail', array('id' => $r['id'])); ?>" class="rekomendasi-item">
                <strong><?php echo e($r['nama_menu']); ?></strong>
                <span><?php echo harga_kartu_html($r); ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
