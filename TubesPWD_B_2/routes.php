<?php
route('GET',  'home',                'menu',  'index');
route('GET',  'menu',                'menu',  'lengkap');
route('GET',  'promo',               'promo', 'index');
route('POST', 'promo/klaim',         'promo', 'klaim', 'login');
route('GET',  'menu/detail',         'menu',  'detail');
route('POST', 'lokasi/simpan',       'menu',  'simpan_lokasi');

route('POST', 'keranjang/tambah',    'cart',  'tambah', 'login');
route('GET',  'keranjang/tambah-qty', 'cart', 'tambah_qty');
route('GET',  'keranjang/kurang-qty', 'cart', 'kurang_qty');
route('GET',  'keranjang/set-qty',    'cart', 'set_qty');

route('POST', 'checkout/bayar',      'order', 'bayar');
route('GET',  'pesanan/struk',       'order', 'struk',  'login');
route('POST', 'pesanan/ubah',        'order', 'ubah',   'login');
route('POST', 'pesanan/batal',       'order', 'batal',  'login');

route('GET',  'profil',              'profile', 'index',  'login');
route('POST', 'profil',              'profile', 'simpan', 'login');
route('GET',  'profil/pesanan',      'profile', 'pesanan', 'login');

route('GET',  'login',               'auth',  'form_login');
route('POST', 'login',               'auth',  'proses_login');
route('GET',  'register',            'auth',  'form_register');
route('POST', 'register',            'auth',  'proses_register');
route('GET',  'logout',              'auth',  'logout');
route('GET',  'api/cek-user',        'auth',  'cek_user');

route('GET',  'kasir',                'kasir', 'index',      'kasir');
route('POST', 'kasir/tambah',         'kasir', 'tambah',     'kasir');
route('GET',  'kasir/tambah-qty',     'kasir', 'tambah_qty', 'kasir');
route('GET',  'kasir/kurang-qty',     'kasir', 'kurang_qty', 'kasir');
route('GET',  'kasir/set-qty',        'kasir', 'set_qty',    'kasir');
route('POST', 'kasir/kosongkan',      'kasir', 'kosongkan',  'kasir');
route('POST', 'kasir/simpan',         'kasir', 'simpan',     'kasir');
route('GET',  'kasir/riwayat',        'kasir', 'riwayat',    'kasir');
route('GET',  'kasir/struk',          'kasir', 'struk',      'kasir');

route('GET',  'admin',                'admin_dashboard', 'index', 'admin');

route('GET',  'admin/pesanan',        'admin_order', 'index',       'kasir');
route('POST', 'admin/pesanan/status', 'admin_order', 'ubah_status', 'kasir');
route('POST', 'admin/pesanan/hapus',  'admin_order', 'hapus',       'admin');

route('GET',  'admin/menu',           'admin_menu', 'index',  'admin');
route('GET',  'admin/menu/tambah',    'admin_menu', 'form_tambah', 'admin');
route('POST', 'admin/menu/tambah',    'admin_menu', 'tambah', 'admin');
route('POST', 'admin/menu/update',    'admin_menu', 'update', 'admin');
route('POST', 'admin/menu/hapus',     'admin_menu', 'hapus',  'admin');

route('GET',  'admin/promo',          'admin_promo', 'index',   'admin');
route('GET',  'admin/promo/tambah',   'admin_promo', 'form_tambah', 'admin');
route('POST', 'admin/promo/tambah',   'admin_promo', 'tambah',  'admin');
route('GET',  'admin/promo/edit',     'admin_promo', 'edit',    'admin');
route('POST', 'admin/promo/update',   'admin_promo', 'update',  'admin');
route('POST', 'admin/promo/hapus',    'admin_promo', 'hapus',   'admin');
route('POST', 'admin/promo/berikan',  'admin_promo', 'berikan', 'admin');

route('GET',  'admin/pengaturan',       'admin_pengaturan', 'index',      'admin');
route('GET',  'admin/pengaturan/tambah',       'admin_pengaturan', 'form_tambah', 'admin');
route('POST', 'admin/pengaturan/media-tambah', 'admin_pengaturan', 'media_tambah', 'admin');
route('POST', 'admin/pengaturan/media-hapus',  'admin_pengaturan', 'media_hapus',  'admin');
route('POST', 'admin/pengaturan/detik',       'admin_pengaturan', 'detik',       'admin');
?>
