<?php
function users_cari_login($isian) {
    return db_satu('SELECT id, nama_lengkap, password, role, foto_profil FROM users WHERE username = ? OR email = ? OR no_hp = ? LIMIT 1', 'sss', array($isian, $isian, $isian));
}

function users_cari($id) {
    return db_satu('SELECT * FROM users WHERE id = ?', 'i', array($id));
}

function users_kolom_ada($kolom, $nilai) {
    if ($kolom != 'username' && $kolom != 'email') {
        return false;
    }
    $baris = db_satu('SELECT id FROM users WHERE ' . $kolom . ' = ? LIMIT 1', 's', array($nilai));
    return $baris != null;
}

function users_username_atau_email_ada($username, $email) {
    $baris = db_satu('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1', 'ss', array($username, $email));
    return $baris != null;
}

function users_email_dipakai_lain($email, $id) {
    $baris = db_satu('SELECT id FROM users WHERE email = ? AND id <> ? LIMIT 1', 'si', array($email, $id));
    return $baris != null;
}

function users_buat($nama, $username, $email, $passwordAman, $noHp) {
    $jumlah = db_jalankan('INSERT INTO users (nama_lengkap, username, email, password, no_hp) VALUES (?, ?, ?, ?, ?)', 'sssss', array($nama, $username, $email, $passwordAman, $noHp));
    if ($jumlah > 0) {
        return db_id_terakhir();
    }
    return 0;
}

function users_update_profil($id, $nama, $email, $noHp) {
    db_jalankan('UPDATE users SET nama_lengkap = ?, email = ?, no_hp = ? WHERE id = ?', 'sssi', array($nama, $email, $noHp, $id));
}

function users_update_foto($id, $foto) {
    db_jalankan('UPDATE users SET foto_profil = ? WHERE id = ?', 'si', array($foto, $id));
}
?>
