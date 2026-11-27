<?php
global $dataLokasi;
?>
<div class="pos">

    <div class="pos-menu">
        <div class="toolbar toolbar-flat" style="margin-top:0;">
            <input type="text" id="searchMenu" class="search-input" placeholder="Cari menu..." onkeyup="cariMenu()" />
            <div class="pills">
                <button type="button" class="pill active" onclick="filterKategori('Semua', this)">Semua</button>
                <button type="button" class="pill" onclick="filterKategori('Basic', this)">Basic</button>
                <button type="button" class="pill" onclick="filterKategori('Signature', this)">Signature</button>
                <button type="button" class="pill" onclick="filterKategori('Flavour', this)">Flavour</button>
                <button type="button" class="pill" onclick="filterKategori('Tea', this)">Tea</button>
                <button type="button" class="pill" onclick="filterKategori('Powder', this)">Powder</button>
                <button type="button" class="pill" onclick="filterKategori('Pastry', this)">Pastry</button>
            </div>
        </div>

        <?php foreach ($menuPerGrup as $grup => $daftarMenu): ?>
        <div class="group-section">
            <div class="group-title"><?php echo e($grup); ?></div>
            <div class="pos-grid">
                <?php foreach ($daftarMenu as $m): ?>
                <button type="button" class="pos-item item-menu"
                    data-tag="<?php echo e($m['filter_tag']); ?>"
                    data-name="<?php echo e(strtolower($m['nama_menu'])); ?>"
                    data-id="<?php echo (int)$m['id']; ?>"
                    data-nama="<?php echo e($m['nama_menu']); ?>"
                    data-grup="<?php echo e($m['grup_menu']); ?>"
                    data-hot="<?php echo (int)$m['harga_hot']; ?>"
                    data-ice="<?php echo (int)$m['harga_ice']; ?>"
                    onclick="bukaModalPos(this)">
                    <strong><?php echo e($m['nama_menu']); ?></strong>
                    <span><?php echo harga_kartu($m); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="pos-order">
        <div class="white-card">
            <div class="card-title">Pesanan saat ini</div>

            <?php if (count($isiKeranjang) == 0): ?>
                <p class="muted">Belum ada menu. Klik menu di sebelah kiri untuk menambahkan.</p>
            <?php else: ?>
                <?php foreach ($isiKeranjang as $no => $item): ?>
                <div class="cart-line">
                    <div>
                        <strong><?php echo e($item['nama']); ?></strong>
                        <small><?php echo e($item['penyajian']); ?><?php if ($item['catatan'] != '') echo ' - ' . e($item['catatan']); ?></small>
                        <span class="price"><?php echo rupiah($item['harga'] * $item['qty']); ?></span>
                    </div>
                    <div class="cart-qty">
                        <a href="<?php echo url('kasir/kurang-qty', array('i' => $no)); ?>">-</a>
                        <input type="text" class="qty-ketik" value="<?php echo (int)$item['qty']; ?>" maxlength="3" onchange="setQtyKeranjang(this, '<?php echo url('kasir/set-qty', array('i' => $no)); ?>')" onkeydown="qtyEnter(event, this)" />
                        <a href="<?php echo url('kasir/tambah-qty', array('i' => $no)); ?>">+</a>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="sum-row total">
                    <span>Total</span>
                    <span id="posTotal" data-total="<?php echo $subtotal; ?>"><?php echo rupiah($subtotal); ?></span>
                </div>

                <form method="post" action="<?php echo url('kasir/kosongkan'); ?>" style="margin-top:6px;">
                    <button type="submit" class="btn-link-danger" data-konfirm="Kosongkan pesanan ini?">Kosongkan pesanan</button>
                </form>
            <?php endif; ?>
        </div>

        <?php if (count($isiKeranjang) > 0): ?>
        <div class="white-card">
            <form method="post" action="<?php echo url('kasir/simpan'); ?>" onsubmit="return cekUangDiterima()">
                <span class="opt-label">Nama pelanggan (boleh kosong)</span>
                <input type="text" name="nama_pelanggan" class="input" placeholder="Pelanggan" />

                <span class="opt-label">Lokasi outlet</span>
                <select name="lokasi" class="input">
                    <?php foreach ($dataLokasi as $nama => $info): ?>
                    <option value="<?php echo e($nama); ?>"<?php if ($_SESSION['lokasi_pilihan'] == $nama) echo ' selected="selected"'; ?>><?php echo e($nama); ?></option>
                    <?php endforeach; ?>
                </select>

                <span class="opt-label">Tipe pesanan</span>
                <select name="tipe_pesanan" class="input">
                    <option value="Dine in">Dine in</option>
                    <option value="Take away">Take away</option>
                </select>

                <span class="opt-label">Metode pembayaran</span>
                <select name="metode_bayar" id="metodeBayar" class="input" onchange="hitungKembalian()">
                    <?php foreach ($metodeBayar as $mb): ?>
                    <option value="<?php echo e($mb); ?>"><?php echo e($mb); ?></option>
                    <?php endforeach; ?>
                </select>

                <div id="boxTunai">
                    <span class="opt-label">Uang diterima</span>
                    <input type="text" name="uang_diterima" id="uangDiterima" class="input" placeholder="Contoh: 50000" maxlength="9" onkeyup="hitungKembalian()" />
                    <div class="sum-row" style="margin-bottom:12px;"><span>Kembalian</span><strong id="txtKembalian">Rp0</strong></div>
                </div>

                <button type="submit" class="btn-full caramel">Simpan Transaksi</button>
            </form>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal" id="modalPos">
    <div class="modal-box">
        <button type="button" class="modal-close" onclick="tutupModalPos()" aria-label="Tutup"></button>
        <div class="modal-title" id="mTitle">-</div>
        <div class="modal-price" id="mHarga">Rp0</div>

        <form method="post" action="<?php echo url('kasir/tambah'); ?>">
            <input type="hidden" name="menu_id" id="inMenuId" />
            <input type="hidden" name="penyajian" id="inPenyajian" value="Ice" />
            <input type="hidden" id="inHarga" value="0" />
            <input type="hidden" name="gula" id="inGula" value="Normal" />
            <input type="hidden" name="es" id="inEs" value="Normal" />

            <div id="boxPenyajian">
                <span class="opt-label">Penyajian</span>
                <div class="chips">
                    <button type="button" class="chip selected" id="btnIce" onclick="pilihPenyajian('Ice')">Ice</button>
                    <button type="button" class="chip" id="btnHot" onclick="pilihPenyajian('Hot')">Hot</button>
                </div>
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
            <input type="text" name="catatan" id="inCatatan" class="input" placeholder="Contoh: tanpa sedotan" />

            <div class="qty-row">
                <div class="qty-box">
                    <button type="button" onclick="ubahQty(-1)">-</button>
                    <input type="text" name="qty" id="inQty" value="1" maxlength="3" onkeyup="cekQty()" onchange="cekQty()" />
                    <button type="button" onclick="ubahQty(1)">+</button>
                </div>
                <button type="submit" id="btnTambah" data-label="Tambah" class="btn-full">Tambah</button>
            </div>
        </form>
    </div>
</div>
