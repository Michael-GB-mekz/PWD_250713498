var userOk = false;
var emailOk = false;
var hpOk = true;
var passOk = false;

function tampilPesan(kolom, pesan, teks, status) {
    kolom.className = 'input';
    pesan.className = '';
    pesan.innerHTML = '';
    if (status == null) {
        return;
    }
    if (status) {
        kolom.className = 'input input-valid';
        pesan.className = 'msg-valid';
    } else {
        kolom.className = 'input input-error';
        pesan.className = 'msg-error';
    }
    pesan.innerHTML = teks;
}

function updateTombol() {
    var tombol = document.getElementById('btnDaftar');
    if (userOk && emailOk && hpOk && passOk) {
        tombol.disabled = false;
    } else {
        tombol.disabled = true;
    }
}

function cekKeServer(field, kolom, pesan, label) {
    var permintaan = new XMLHttpRequest();
    permintaan.onreadystatechange = function () {
        if (permintaan.readyState == 4 && permintaan.status == 200) {
            var hasil = JSON.parse(permintaan.responseText);
            var tersedia = !hasil.exists;
            if (field == 'username') {
                userOk = tersedia;
            } else {
                emailOk = tersedia;
            }
            if (tersedia) {
                tampilPesan(kolom, pesan, label + ' tersedia.', true);
            } else {
                tampilPesan(kolom, pesan, label + ' ini sudah dipakai!', false);
            }
            updateTombol();
        }
    };
    permintaan.open('GET', 'index.php?r=api/cek-user&field=' + field + '&value=' + encodeURIComponent(kolom.value), true);
    permintaan.send();
}

function cekUsername() {
    var kolom = document.getElementById('regUser');
    var pesan = document.getElementById('msgUser');
    var nilai = kolom.value;
    userOk = false;

    if (nilai == '') {
        tampilPesan(kolom, pesan, '', null);
    } else if (!/^[A-Za-z0-9_.]{3,30}$/.test(nilai)) {
        tampilPesan(kolom, pesan, 'Username 3-30 karakter: huruf, angka, titik, atau underscore.', false);
    } else {
        cekKeServer('username', kolom, pesan, 'Username');
    }
    updateTombol();
}

function cekEmail() {
    var kolom = document.getElementById('regEmail');
    var pesan = document.getElementById('msgEmail');
    var nilai = kolom.value;
    emailOk = false;

    if (nilai == '') {
        tampilPesan(kolom, pesan, '', null);
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(nilai)) {
        tampilPesan(kolom, pesan, 'Format email tidak valid.', false);
    } else {
        cekKeServer('email', kolom, pesan, 'Email');
    }
    updateTombol();
}

function cekHp() {
    var kolom = document.getElementById('regHp');
    var pesan = document.getElementById('msgHp');
    var nilai = kolom.value;

    if (nilai == '') {
        hpOk = true;
        tampilPesan(kolom, pesan, '', null);
    } else if (/^[0-9+]{8,15}$/.test(nilai)) {
        hpOk = true;
        tampilPesan(kolom, pesan, 'Nomor valid.', true);
    } else {
        hpOk = false;
        tampilPesan(kolom, pesan, 'Nomor harus 8-15 digit angka.', false);
    }
    updateTombol();
}

function cekPassword() {
    var kolom = document.getElementById('regPass');
    var pesan = document.getElementById('msgPass');
    var nilai = kolom.value;

    if (nilai == '') {
        passOk = false;
        tampilPesan(kolom, pesan, '', null);
    } else if (nilai.length < 6) {
        passOk = false;
        tampilPesan(kolom, pesan, 'Password minimal 6 karakter.', false);
    } else {
        passOk = true;
        tampilPesan(kolom, pesan, 'Password cukup.', true);
    }
    updateTombol();
}

window.onload = updateTombol;
