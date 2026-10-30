function fn_ValForm() {
    var sMsg = "";

    var nama = document.getElementById("nama").value;
    var email = document.getElementById("email").value;
    var umur = document.getElementById("umur").value;
    var kategori = document.getElementById("kategori").value;

    nama = nama.trim();
    email = email.trim();
    umur = umur.trim();

    if (nama == ""){
        sMsg += "\n- Nama belum diisi";
    }

    if (email == ""){
        sMsg += "\n- Email belum diisi";
    }

    if (umur == ""){
        sMsg += "\n- Umur belum diisi";
    }

    if (kategori == ""){
        sMsg += "\n- Paket member belum dipilih";
    }

    var polaEmail = /^[a-z0-9][a-z0-9_\.-]{0,}[a-z0-9]@[a-z0-9][a-z0-9_\.-]{0,}[a-z0-9][\.][a-z0-9]{2,4}$/;

    if (email != "" && !polaEmail.test(email)) {
        sMsg += "\n- Format email tidak valid (gunakan huruf kecil)";
    }

    if (umur != ""){
        if (!/^[0-9]+$/.test(umur)) {
            sMsg += "\n- Umur harus berupa angka";
        } else if (Number(umur) < 17 || Number(umur) > 65) {
            sMsg += "\n- Umur harus antara 17 sampai 65 tahun";
        }
    }

    if (sMsg != "") {
        alert("Peringatan:" + sMsg);
        return false;
    }

    var info = "";

    if (Number(umur) <= 25){
        info = "Paket Pelajar";
    } else {
        info = "Paket Umum";
    }

    var pilihan = "";

    switch (kategori) {
        case "S":
            pilihan = "Silver";
            break;

        case "G":
            pilihan = "Gold";
            break;

        case "P":
            pilihan = "Platinum";
            break;

        default:
            alert("Paket member tidak valid");
            return false;
    }

    alert("Terima kasih " + nama + " ! Anda terdaftar di paket " + pilihan + " (" + info + ").");

    return true;
}