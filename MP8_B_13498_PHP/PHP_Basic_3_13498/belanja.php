<h2>Belanja</h2>
<h3>Daftar Kategori:</h3>
<form action="" method="POST">
    <p>Makanan dan Minuman: <br/>
        <select name="makanan_minuman">
            <option value="">--Pilih--</option>
            <option value="pop mie">pop mie</option>
            <option value="air mineral">air mineral</option>
        </select>
    </p>
    <p>Alat Tulis: <br/>
        <select name="alat_tulis">
            <option value="">--Pilih--</option>
            <option value="pulpen">pulpen</option>
            <option value="tipe-x">tipe-x</option>
        </select>
    </p>
    <button type="submit">Kirim</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $makanan = $_POST['makanan_minuman'] ?? '';
    $alatTulis = $_POST['alat_tulis'] ?? '';
    $errors = [];

    if (empty($makanan) || empty($alatTulis)) {
        $errors[] = "Harap pilih produk makanan/minuman dan alat tulis.";
    }

    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>!! " . htmlspecialchars($err) . " !!</p>";
        }
    } else {
        $hargaMakanan = 0;
        if ($makanan == 'pop mie') {
            $hargaMakanan = 5000;
        } elseif ($makanan == 'air mineral') {
            $hargaMakanan = 4000;
        }

        $hargaAlatTulis = 0;
        if ($alatTulis == 'pulpen') {
            $hargaAlatTulis = 2000;
        } elseif ($alatTulis == 'tipe-x') {
            $hargaAlatTulis = 8000;
        }

        $totalBayar = $hargaMakanan + $hargaAlatTulis;

        function dapatDiskon($totalBayar) {
            if ($totalBayar >= 10000) {
                return 0.05;
            } else {
                return 0;
            }
        }

        function totalHarga($diskon, $totalBayar) {
            return $totalBayar - ($totalBayar * $diskon);
        }

        $diskon = dapatDiskon($totalBayar);
        $finalHarga = totalHarga($diskon, $totalBayar);

        echo "<h3>Informasi Belanja</h3>";
        echo "<p>Makanan dan Minuman: " . htmlspecialchars($makanan) . " (Rp " . $hargaMakanan . ")</p>";
        echo "<p>Alat Tulis: " . htmlspecialchars($alatTulis) . " (Rp " . $hargaAlatTulis . ")</p>";
        echo "<p>Total Harga (setelah diskon): Rp " . htmlspecialchars($finalHarga) . "</p>";
        echo "<p>Diskon: " . htmlspecialchars($diskon * 100) . "%</p>";

        if ($diskon == 0) {
            echo "<p>Maaf tidak ada diskon yang diberikan.</p>";
        } else {
            echo "<p>Selamat! Anda mendapatkan diskon " . htmlspecialchars($diskon * 100) . "%.ခင်</p>";
        }
    }
} else {
    echo "<p>Silahkan pilih produk yang ingin dibeli.</p>";
}
?>