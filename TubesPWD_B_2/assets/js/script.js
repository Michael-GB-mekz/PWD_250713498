var hargaHot = 0;
var hargaIce = 0;
var kategoriAktif = 'Semua';
var diskonPersen = 0;

function formatRupiah(angka) {
    var teks = String(angka);
    var hasil = '';
    var hitung = 0;
    for (var i = teks.length - 1; i >= 0; i--) {
        hasil = teks.charAt(i) + hasil;
        hitung++;
        if (hitung % 3 == 0 && i != 0) {
            hasil = '.' + hasil;
        }
    }
    return 'Rp' + hasil;
}

function aturPilihan(grup, hot, ice) {
    hargaHot = hot;
    hargaIce = ice;

    var kotakHotIce = document.getElementById('boxPenyajian');
    var kotakGulaEs = document.getElementById('boxGulaEs');
    var btnIce = document.getElementById('btnIce');
    var btnHot = document.getElementById('btnHot');
    var infoHemat = document.getElementById('infoHemat');

    if (grup == 'CROISSANT' || grup == 'SOURDOUGH' || grup == 'JOGJA BUN') {
        kotakHotIce.style.display = 'none';
        kotakGulaEs.style.display = 'none';
        setHarga('Regular', hargaHot);
        return;
    }

    kotakHotIce.style.display = 'block';
    kotakGulaEs.style.display = 'block';

    var diskonHot = hargaDiskon(hargaHot);
    var diskonIce = hargaDiskon(hargaIce);
    var selisih = Math.abs(diskonHot - diskonIce);

    if (hargaHot > 0) {
        btnHot.style.display = 'inline-block';
        btnHot.innerHTML = 'Hot - ' + formatRupiah(diskonHot);
        if (hargaIce > 0 && diskonHot > diskonIce) {
            btnHot.innerHTML += ' (+' + formatRupiah(selisih) + ')';
        }
    } else {
        btnHot.style.display = 'none';
    }

    if (hargaIce > 0) {
        btnIce.style.display = 'inline-block';
        btnIce.innerHTML = 'Ice - ' + formatRupiah(diskonIce);
        if (hargaHot > 0 && diskonIce > diskonHot) {
            btnIce.innerHTML += ' (+' + formatRupiah(selisih) + ')';
        }
    } else {
        btnIce.style.display = 'none';
    }

    var termurah = 'Hot';
    if (hargaHot <= 0 || (hargaIce > 0 && diskonIce < diskonHot)) {
        termurah = 'Ice';
    }
    pilihPenyajian(termurah);

    if (infoHemat != null) {
        if (hargaHot > 0 && hargaIce > 0 && selisih > 0) {
            var lainnya = 'Ice';
            if (termurah == 'Ice') {
                lainnya = 'Hot';
            }
            infoHemat.innerHTML = 'Pilih ' + termurah + ' dan hemat ' + formatRupiah(selisih) + ' dibanding ' + lainnya + '.';
        } else {
            infoHemat.innerHTML = '';
        }
    }
}

function siapkanDetail() {
    var form = document.getElementById('formDetail');
    if (form == null) {
        return;
    }
    diskonPersen = parseInt(form.getAttribute('data-diskon'));
    aturPilihan(form.getAttribute('data-grup'), parseInt(form.getAttribute('data-hot')), parseInt(form.getAttribute('data-ice')));
}

var slideAktif = 0;
var pewaktuSlide = null;
var detikSlide = 4;

function tampilkanSlide(nomor) {
    var slide = document.getElementsByClassName('hero-slide');
    var titik = document.getElementsByClassName('hero-titik');
    for (var i = 0; i < slide.length; i++) {
        if (i == nomor) {
            slide[i].className = 'hero-img hero-slide aktif';
        } else {
            slide[i].className = 'hero-img hero-slide';
        }
        if (slide[i].tagName == 'VIDEO') {
            slide[i].onended = null;
            if (i == nomor) {
                slide[i].muted = true;
                slide[i].currentTime = 0;
                slide[i].play();
            } else {
                slide[i].pause();
            }
        }
    }
    for (var j = 0; j < titik.length; j++) {
        if (j == nomor) {
            titik[j].className = 'hero-titik aktif';
        } else {
            titik[j].className = 'hero-titik';
        }
    }
    slideAktif = nomor;
}

function jadwalkanBerikut() {
    clearTimeout(pewaktuSlide);
    var slide = document.getElementsByClassName('hero-slide');
    if (slide.length < 2) {
        return;
    }
    pewaktuSlide = setTimeout(slideBerikut, detikSlide * 1000);
}

function slideBerikut() {
    var jumlah = document.getElementsByClassName('hero-slide').length;
    tampilkanSlide((slideAktif + 1) % jumlah);
    jadwalkanBerikut();
}

function mulaiSlideHero() {
    var kotak = document.getElementById('heroSlides');
    if (kotak == null) {
        return;
    }
    var slide = document.getElementsByClassName('hero-slide');
    if (slide.length == 0) {
        return;
    }
    detikSlide = parseInt(kotak.getAttribute('data-detik'));
    if (isNaN(detikSlide) || detikSlide < 2) {
        detikSlide = 4;
    }

    if (slide.length == 1) {
        if (slide[0].tagName == 'VIDEO') {
            slide[0].muted = true;
            slide[0].loop = true;
            slide[0].play();
        }
        return;
    }

    tampilkanSlide(0);
    jadwalkanBerikut();
}

function pilihSlide(nomor) {
    tampilkanSlide(nomor);
    jadwalkanBerikut();
}

window.onload = function () {
    siapkanDetail();
    mulaiSlideHero();
    pusatkanAngka();
};

function pusatkanAngka() {
    var kanvas = document.createElement('canvas');
    var ctx = kanvas.getContext('2d');
    var daftar = document.querySelectorAll('.notif, .step-dot, .badge-pct, .promo-persen');
    for (var i = 0; i < daftar.length; i++) {
        var el = daftar[i];
        el.style.padding = '';
        var teks = el.textContent.replace(/\s+/g, '');
        var gaya = window.getComputedStyle(el);
        ctx.font = gaya.fontWeight + ' ' + gaya.fontSize + ' ' + gaya.fontFamily;
        var m = ctx.measureText(teks);
        if (m.actualBoundingBoxRight === undefined || m.fontBoundingBoxAscent === undefined) {
            continue;
        }

        var geserX = m.width / 2 - (m.actualBoundingBoxRight - m.actualBoundingBoxLeft) / 2;

        var tinggiBaris = parseFloat(gaya.fontSize);
        var garisDasar = (tinggiBaris - (m.fontBoundingBoxAscent + m.fontBoundingBoxDescent)) / 2 + m.fontBoundingBoxAscent;
        var pusatTinta = garisDasar + (m.actualBoundingBoxDescent - m.actualBoundingBoxAscent) / 2;
        var geserY = tinggiBaris / 2 - pusatTinta;

        var kiri = parseFloat(gaya.paddingLeft) + (geserX > 0 ? geserX * 2 : 0);
        var kanan = parseFloat(gaya.paddingRight) + (geserX < 0 ? -geserX * 2 : 0);
        var atas = parseFloat(gaya.paddingTop) + (geserY > 0 ? geserY * 2 : 0);
        var bawah = parseFloat(gaya.paddingBottom) + (geserY < 0 ? -geserY * 2 : 0);
        el.style.padding = atas + 'px ' + kanan + 'px ' + bawah + 'px ' + kiri + 'px';
    }
}

function pilihPenyajian(tipe) {
    var btnIce = document.getElementById('btnIce');
    var btnHot = document.getElementById('btnHot');
    if (tipe == 'Ice') {
        btnIce.className = 'chip selected';
        btnHot.className = 'chip';
        setHarga('Ice', hargaIce);
    } else {
        btnIce.className = 'chip';
        btnHot.className = 'chip selected';
        setHarga('Hot', hargaHot);
    }

    var kotakEs = document.getElementById('boxEs');
    if (kotakEs != null) {
        kotakEs.style.display = (tipe == 'Ice') ? 'block' : 'none';
    }
}

function hargaDiskon(harga) {
    return Math.round(harga * (100 - diskonPersen) / 100);
}

function tampilHarga(harga) {
    if (diskonPersen > 0) {
        return '<s class="harga-coret">' + formatRupiah(harga) + '</s> ' + formatRupiah(hargaDiskon(harga));
    }
    return formatRupiah(harga);
}

function setHarga(tipe, harga) {
    document.getElementById('inPenyajian').value = tipe;
    document.getElementById('inHarga').value = harga;
    document.getElementById('mHarga').innerHTML = tampilHarga(harga);
    updateTombolTambah();
}

function pilihChip(tombol, idInput, nilai) {
    var saudara = tombol.parentNode.getElementsByTagName('button');
    for (var i = 0; i < saudara.length; i++) {
        saudara[i].className = 'chip';
    }
    tombol.className = 'chip selected';
    document.getElementById(idInput).value = nilai;
}

function ubahQty(selisih) {
    var qty = parseInt(document.getElementById('inQty').value) + selisih;
    if (isNaN(qty) || qty < 1) {
        qty = 1;
    }
    if (qty > 999) {
        qty = 999;
    }
    document.getElementById('inQty').value = qty;
    updateTombolTambah();
}

function cekQty() {
    var kolom = document.getElementById('inQty');
    var qty = parseInt(kolom.value.replace(/[^0-9]/g, ''));
    if (isNaN(qty) || qty < 1) {
        qty = 1;
    }
    if (qty > 999) {
        qty = 999;
    }
    kolom.value = qty;
    updateTombolTambah();
}

function setQtyKeranjang(kolom, urlDasar) {
    var qty = parseInt(kolom.value.replace(/[^0-9]/g, ''));
    if (isNaN(qty)) {
        qty = 1;
    }
    window.location.href = urlDasar + '&qty=' + qty;
}

function qtyEnter(event, kolom) {
    if (event.keyCode == 13) {
        event.preventDefault();
        kolom.onchange();
    }
}

function updateTombolTambah() {
    var harga = hargaDiskon(parseInt(document.getElementById('inHarga').value));
    var qty = parseInt(document.getElementById('inQty').value);
    var tombol = document.getElementById('btnTambah');
    tombol.innerHTML = tombol.getAttribute('data-label') + ' - ' + formatRupiah(harga * qty);
}

function bukaKeranjang() {
    var modal = document.getElementById('modalKeranjang');
    if (modal == null) {
        return true;
    }
    kembaliKeKeranjang();
    modal.className = 'modal show';
    return false;
}

function keStepPembayaran() {
    document.getElementById('stepKeranjang').style.display = 'none';
    document.getElementById('stepPembayaran').style.display = 'block';
}

function tampilKomitmen() {
    var radio = document.getElementsByName('metode_bayar');
    var kasir = false;
    for (var i = 0; i < radio.length; i++) {
        if (radio[i].checked && radio[i].value == 'Bayar di Kasir') {
            kasir = true;
        }
    }
    document.getElementById('boxKomitmen').style.display = kasir ? 'block' : 'none';
}

function hitungTotalBayar() {
    var ringkasan = document.getElementById('ringkasanBayar');
    if (ringkasan == null) {
        return;
    }
    var subtotal = parseInt(ringkasan.getAttribute('data-subtotal'));
    var persen = 0;
    var radio = document.getElementsByName('promo_id');
    for (var i = 0; i < radio.length; i++) {
        if (radio[i].checked) {
            persen = parseInt(radio[i].getAttribute('data-persen'));
        }
    }
    var diskon = Math.round(subtotal * persen / 100);
    document.getElementById('barisDiskon').style.display = diskon > 0 ? 'flex' : 'none';
    document.getElementById('txtDiskon').innerHTML = '-' + formatRupiah(diskon);
    document.getElementById('txtTotal2').innerHTML = formatRupiah(subtotal - diskon);
}

function kembaliKeKeranjang() {
    document.getElementById('stepKeranjang').style.display = 'block';
    document.getElementById('stepPembayaran').style.display = 'none';
}

function tutupModal() {
    document.getElementById('modalKeranjang').className = 'modal';
}

function filterKategori(tag, tombol) {
    var semuaPill = document.getElementsByClassName('pill');
    for (var i = 0; i < semuaPill.length; i++) {
        semuaPill[i].className = 'pill';
    }
    tombol.className = 'pill active';
    kategoriAktif = tag;
    terapkanFilter();
}

function cariMenu() {
    terapkanFilter();
}

function terapkanFilter() {
    var kata = document.getElementById('searchMenu').value.toLowerCase();
    var semuaMenu = document.getElementsByClassName('item-menu');

    for (var i = 0; i < semuaMenu.length; i++) {
        var tag = semuaMenu[i].getAttribute('data-tag');
        var nama = semuaMenu[i].getAttribute('data-name');
        var cocokKategori = (kategoriAktif == 'Semua' || kategoriAktif == tag);
        var cocokNama = (nama.indexOf(kata) != -1);

        if (cocokKategori && cocokNama) {
            semuaMenu[i].style.display = '';
        } else {
            semuaMenu[i].style.display = 'none';
        }
    }

    var semuaGrup = document.getElementsByClassName('group-section');
    for (var g = 0; g < semuaGrup.length; g++) {
        var baris = semuaGrup[g].getElementsByClassName('item-menu');
        var adaTampil = false;
        for (var b = 0; b < baris.length; b++) {
            if (baris[b].style.display != 'none') {
                adaTampil = true;
            }
        }
        if (adaTampil) {
            semuaGrup[g].style.display = '';
        } else {
            semuaGrup[g].style.display = 'none';
        }
    }
}

function hitungJarak(lat1, lon1, lat2, lon2) {
    var radius = 6371000;
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return Math.round(radius * c);
}

function prosesLokasi(lat, lon) {
    var pilihan = document.getElementById('selectKampus');
    var daftar = pilihan.getElementsByTagName('option');
    var terdekat = 0;
    var jarakTerdekat = 999999999;

    for (var i = 0; i < daftar.length; i++) {
        var latLokasi = parseFloat(daftar[i].getAttribute('data-lat'));
        var lonLokasi = parseFloat(daftar[i].getAttribute('data-lon'));
        var jarak = hitungJarak(lat, lon, latLokasi, lonLokasi);
        if (jarak < jarakTerdekat) {
            jarakTerdekat = jarak;
            terdekat = i;
        }
    }

    pilihan.selectedIndex = terdekat;
    document.getElementById('inputJarak').value = jarakTerdekat + ' m dari GPS';
    document.getElementById('formLokasi').submit();
}

function gantiLokasi() {
    var pilihan = document.getElementById('selectKampus');
    var opsi = pilihan.options[pilihan.selectedIndex];
    var teks = 'Manual';

    try {
        var tersimpan = sessionStorage.getItem('posisiGPS');
        if (tersimpan) {
            var pos = JSON.parse(tersimpan);
            var jarak = hitungJarak(pos.lat, pos.lon, parseFloat(opsi.getAttribute('data-lat')), parseFloat(opsi.getAttribute('data-lon')));
            teks = jarak + ' m dari GPS';
        }
    } catch (e) {
        teks = 'Manual';
    }

    document.getElementById('inputJarak').value = teks;
    document.getElementById('formLokasi').submit();
}

function cekGPS() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function (posisi) {
                try {
                    sessionStorage.setItem('posisiGPS', JSON.stringify({ lat: posisi.coords.latitude, lon: posisi.coords.longitude }));
                } catch (e) {}
                prosesLokasi(posisi.coords.latitude, posisi.coords.longitude);
            },
            function () {
                prosesLokasi(-7.7798, 110.4156);
            }
        );
    } else {
        prosesLokasi(-7.7798, 110.4156);
    }
}

function tampilkanPeta(kataPeta) {
    document.getElementById('boxPeta').style.display = 'block';
    document.getElementById('iframePeta').src = 'https://www.google.com/maps?q=' + encodeURIComponent(kataPeta) + '&output=embed';
}

var kotakAksiOk = null;

function buatKotakPesan() {
    if (document.getElementById('kotakPesan')) {
        return;
    }
    var latar = document.createElement('div');
    latar.id = 'kotakPesan';
    latar.className = 'kotak-pesan';
    latar.innerHTML =
        '<div class="kotak-isi" role="dialog" aria-modal="true">' +
        '<div class="kotak-ikon">!</div>' +
        '<h3 id="kotakJudul"></h3>' +
        '<p id="kotakTeks"></p>' +
        '<div class="kotak-tombol">' +
        '<button type="button" class="kotak-batal" id="kotakBatal">Batal</button>' +
        '<button type="button" class="kotak-ok" id="kotakOk">OK</button>' +
        '</div></div>';
    document.body.appendChild(latar);

    document.getElementById('kotakBatal').onclick = tutupKotakPesan;
    document.getElementById('kotakOk').onclick = function () {
        var aksi = kotakAksiOk;
        tutupKotakPesan();
        if (aksi) {
            aksi();
        }
    };
    latar.onclick = function (e) {
        if (e.target == latar) {
            tutupKotakPesan();
        }
    };
    document.onkeydown = function (e) {
        e = e || window.event;
        if (e.keyCode == 27) {
            tutupKotakPesan();
        }
    };
}

function tutupKotakPesan() {
    var latar = document.getElementById('kotakPesan');
    if (latar) {
        latar.className = 'kotak-pesan';
    }
    kotakAksiOk = null;
}

function bukaKotakPesan(judul, teks, labelOk, aksi, bahaya) {
    buatKotakPesan();
    document.getElementById('kotakJudul').textContent = judul;
    document.getElementById('kotakTeks').textContent = teks;
    document.getElementById('kotakOk').textContent = labelOk;
    document.getElementById('kotakBatal').style.display = aksi ? '' : 'none';
    document.getElementById('kotakOk').className = bahaya ? 'kotak-ok bahaya' : 'kotak-ok';
    kotakAksiOk = aksi;
    document.getElementById('kotakPesan').className = 'kotak-pesan tampil';
    document.getElementById('kotakOk').focus();
}

function tampilAlert(teks, judul) {
    bukaKotakPesan(judul ? judul : 'Perhatian', teks, 'OK', null, false);
}

function tampilKonfirmasi(teks, judul, labelOk, aksi, bahaya) {
    bukaKotakPesan(judul, teks, labelOk, aksi, bahaya);
}

document.addEventListener('click', function (e) {
    var el = e.target;
    while (el && el !== document && !(el.getAttribute && el.getAttribute('data-konfirm'))) {
        el = el.parentNode;
    }
    if (!el || el === document) {
        return;
    }
    e.preventDefault();

    var teks = el.getAttribute('data-konfirm');
    var judul = el.getAttribute('data-judul');
    var label = el.getAttribute('data-tombol');
    var bahaya = false;
    var kataAwal = teks.split(' ')[0];

    if (kataAwal == 'Hapus' || kataAwal == 'Batalkan' || kataAwal == 'Kosongkan') {
        bahaya = true;
    }
    if (!judul) {
        judul = 'Konfirmasi';
        if (kataAwal == 'Hapus') {
            judul = 'Hapus data';
        } else if (kataAwal == 'Batalkan') {
            judul = 'Batalkan';
        } else if (kataAwal == 'Kosongkan') {
            judul = 'Kosongkan pesanan';
        }
    }
    if (!label) {
        label = 'Ya, lanjutkan';
        if (kataAwal == 'Hapus') {
            label = 'Ya, hapus';
        }
    }

    tampilKonfirmasi(teks, judul, label, function () {
        if (el.getAttribute('data-tuju')) {
            window.location.href = el.getAttribute('data-tuju');
        } else if (el.tagName.toLowerCase() == 'a') {
            window.location.href = el.href;
        } else if (el.form) {
            el.form.submit();
        }
    }, bahaya);
});
