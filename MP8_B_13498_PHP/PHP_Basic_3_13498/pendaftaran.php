<h2>Pendaftaran Staff</h2>
<form action="" method="POST">
    <p>Nama: <br/><input type="text" name="nama" /></p>
    <p>NPM: <br/><input type="text" name="npm" /></p>
    <p>Pilihan Staff: <br/>
        <select name="pilihan_staff">
            <option value="">--Pilih Staff--</option>
            <option value="kasir">kasir</option>
            <option value="merchandiser">merchandiser</option>
        </select>
    </p>
    <button type="submit">Kirim</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'] ?? '';
    $npm = $_POST['npm'] ?? '';
    $loker = $_POST['pilihan_staff'] ?? '';
    $errors = [];

    if (empty($nama) || empty($npm) || empty($loker)) {
        $errors[] = "Harap lengkapi semua data formulir.";
    }
    if (!preg_match("/^[a-zA-Z\s]*$/", $nama)) {
        $errors[] = "Nama hanya boleh mengandung huruf dan spasi.";
    }
    if (!preg_match("/^[0-9]*$/", $npm)) {
        $errors[] = "NPM hanya boleh mengandung angka.";
    }

    if (!empty($errors)) {
        foreach ($errors as $err) {
            echo "<p style='color:red;'>!! " . htmlspecialchars($err) . " !!</p>";
        }
    } else {
        if ($loker == 'kasir') {
            $jadwalWawancara = "besok pukul 10.00 WIB di market";
        } elseif ($loker == 'merchandiser') {
            $jadwalWawancara = "lusa pukul 12.00 WIB di market";
        } else {
            $jadwalWawancara = "ditentukan kemudian";
        }

        echo "<h3>Informasi Pendaftaran</h3>";
        echo "<p>Nama: " . htmlspecialchars($nama) . "</p>";
        echo "<p>NPM: " . htmlspecialchars($npm) . "</p>";
        echo "<p>Pilihan Staff: " . htmlspecialchars($loker) . "</p>";
        echo "<p>Anda mendaftar sebagai " . htmlspecialchars($loker) . ". Selanjutnya, Anda dapat hadir " . htmlspecialchars($jadwalWawancara) . " untuk wawancara.</p>";
    }
} else {
    echo "<p>Silahkan lengkapi formulir di atas.</p>";
}
?>