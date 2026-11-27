<?php
function admin_order_index() {
    $pesanan = orders_semua();

    $itemPerPesanan = array();
    foreach ($pesanan as $o) {
        $itemPerPesanan[$o['id']] = orders_items($o['id']);
    }

    $data = array(
        'halamanAdmin'   => 'pesanan',
        'judul'          => 'Sales Order',
        'pesanan'        => $pesanan,
        'itemPerPesanan' => $itemPerPesanan,
        'daftarStatus'   => orders_daftar_status()
    );
    view('admin/order/index', $data, 'admin');
}

function admin_order_ubah_status() {
    $status = post('status');
    if (!in_array($status, orders_daftar_status())) {
        flash('error', 'Status tidak valid.');
        redirect('admin/pesanan');
    }
    orders_ubah_status((int)post('order_id'), $status);
    flash('sukses', "Status antrean diperbarui menjadi '" . $status . "'!");
    redirect('admin/pesanan');
}

function admin_order_hapus() {
    $orderId = (int)post('order_id');
    orders_hapus($orderId);
    flash('sukses', 'Pesanan dihapus!');
    redirect('admin/pesanan');
}
?>
