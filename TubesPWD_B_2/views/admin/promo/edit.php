<?php
$batasKlaim = '';
if ($promo['klaim_sampai'] != null) {
    $batasKlaim = date('Y-m-d\\TH:i', strtotime($promo['klaim_sampai']));
}
$nilai = array(
    'nama_promo'    => $promo['nama_promo'],
    'deskripsi'     => $promo['deskripsi'],
    'jenis'         => $promo['jenis'],
    'diskon_persen' => $promo['diskon_persen'],
    'berlaku_hari'  => $promo['berlaku_hari'],
    'batas_pakai'   => $promo['batas_pakai'],
    'kuota'         => $promo['kuota'],
    'klaim_sampai'  => $batasKlaim,
    'aktif'         => $promo['aktif']
);
$aksiForm = url('admin/promo/update');
$idPromo = $promo['id'];
$teksTombol = 'Simpan';
?>
<div class="white-card form-sempit">
    <?php require 'views/admin/promo/_form.php'; ?>
</div>
