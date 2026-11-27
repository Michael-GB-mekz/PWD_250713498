function bukaModalPos(tombol) {
    document.getElementById('inMenuId').value = tombol.getAttribute('data-id');
    document.getElementById('mTitle').innerHTML = tombol.getAttribute('data-nama');
    document.getElementById('inQty').value = 1;
    document.getElementById('inCatatan').value = '';

    document.getElementById('inGula').value = 'Normal';
    document.getElementById('inEs').value = 'Normal';
    var tombolChip = document.getElementById('boxGulaEs').getElementsByTagName('button');
    for (var i = 0; i < tombolChip.length; i++) {
        if (tombolChip[i].innerHTML == 'Normal') {
            tombolChip[i].className = 'chip selected';
        } else {
            tombolChip[i].className = 'chip';
        }
    }

    aturPilihan(tombol.getAttribute('data-grup'), parseInt(tombol.getAttribute('data-hot')), parseInt(tombol.getAttribute('data-ice')));
    updateTombolTambah();
    document.getElementById('modalPos').className = 'modal show';
}

function tutupModalPos() {
    document.getElementById('modalPos').className = 'modal';
}

function hitungKembalian() {
    var kotakTunai = document.getElementById('boxTunai');
    var metode = document.getElementById('metodeBayar');
    if (kotakTunai == null || metode == null) {
        return;
    }

    if (metode.value == 'Tunai') {
        kotakTunai.style.display = 'block';
    } else {
        kotakTunai.style.display = 'none';
        return;
    }

    var kolomUang = document.getElementById('uangDiterima');
    var bersih = kolomUang.value.replace(/[^0-9]/g, '');
    if (kolomUang.value != bersih) {
        kolomUang.value = bersih;
    }

    var total = parseInt(document.getElementById('posTotal').getAttribute('data-total'));
    var uang = parseInt(bersih);
    var teks = document.getElementById('txtKembalian');

    if (isNaN(uang)) {
        teks.innerHTML = 'Rp0';
    } else if (uang < total) {
        teks.innerHTML = 'Uang kurang ' + formatRupiah(total - uang);
    } else {
        teks.innerHTML = formatRupiah(uang - total);
    }
}

function cekUangDiterima() {
    var metode = document.getElementById('metodeBayar');
    if (metode == null || metode.value != 'Tunai') {
        return true;
    }
    var total = parseInt(document.getElementById('posTotal').getAttribute('data-total'));
    var uang = parseInt(document.getElementById('uangDiterima').value);
    if (isNaN(uang) || uang < total) {
        alert('Uang diterima kurang dari total ' + formatRupiah(total) + '.');
        return false;
    }
    return true;
}

var onloadAwal = window.onload;
window.onload = function () {
    if (onloadAwal) {
        onloadAwal();
    }
    hitungKembalian();
};
