<?php
// 1. Katalog Jasa/Sewa Ruang Belajar & Buku
$katalog = [
    "RB01" => ["nama" => "Sewa Ruang Belajar VIP (per jam)", "harga" => 50000],
    "RB02" => ["nama" => "Sewa Ruang Belajar Standard (per jam)", "harga" => 25000],
    "BK01" => ["nama" => "Peminjaman Buku Referensi Eksklusif", "harga" => 15000],
    "BK02" => ["nama" => "Pencetakan Dokumen & Jurnal (per paket)", "harga" => 10000]
];

// 2. Menghitung Total Harga Sebelum Diskon
function hitungTotal($idItem, $kuantitas, $katalogData) {
    if (array_key_exists($idItem, $katalogData)) {
        return $katalogData[$idItem]['harga'] * $kuantitas;
    }
    return 0;
}

// 3. Menghitung Diskon
function hitungDiskon($totalKotor) {
    $diskon = 0;
    if ($totalKotor >= 100000) {
        $diskon = 0.10 * $totalKotor; // Diskon 10%
    }
    return $diskon;
}

// Inisialisasi variabel pemrosesan form
$itemTerpilih = "";
$qty = 1;
$showResult = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemTerpilih = $_POST['item'];
    $qty = intval($_POST['qty']);
    
    if (!empty($itemTerpilih) && $qty > 0) {
        $namaItem = $katalog[$itemTerpilih]['nama'];
        $hargaSatuan = $katalog[$itemTerpilih]['harga'];
        
        // Pemanggilan function
        $totalKotor = hitungTotal($itemTerpilih, $qty, $katalog);
        $diskon = hitungDiskon($totalKotor);
        $totalBersih = $totalKotor - $diskon;
        
        $showResult = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas 13 - Array & Function PHP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 30px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 0 auto 20px auto;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #2c3e50;
            margin-top: 0;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 10px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            color: #34495e;
        }
        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
        }
        button {
            background-color: #85bdcd;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }
        button:hover {
            background-color: #d459a9;
        }
        .table-katalog {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-katalog th, .table-katalog td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-katalog th {
            background-color: #34495e;
            color: white;
        }
        .result-box {
            background-color: #ebf5fb;
            border-left: 5px solid #3498db;
            padding: 15px;
            border-radius: 4px;
        }
        .result-item {
            margin-bottom: 8px;
            font-size: 15px;
            display: flex;
            justify-content: space-between;
        }
        .total-highlight {
            font-size: 18px;
            font-weight: bold;
            color: #c0392b;
            border-top: 2px dashed #bdc3c7;
            padding-top: 8px;
            margin-top: 8px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Katalog Layanan & Sewa</h2>
    <!-- Iterasi Array untuk Menampilkan Katalog -->
    <table class="table-katalog">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($katalog as $kode => $detail): ?>
            <tr>
                <td><strong><?php echo $kode; ?></strong></td>
                <td><?php echo $detail['nama']; ?></td>
                <td>Rp <?php echo number_format($detail['harga'], 0, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="container">
    <h3>Form Transaksi</h3>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        
        <div class="form-group">
            <label for="item">Pilih Layanan/Item:</label>
            <select id="item" name="item" required>
                <option value="">-- Pilih Layanan --</option>
                <?php foreach ($katalog as $kode => $detail): ?>
                    <option value="<?php echo $kode; ?>" <?php if ($itemTerpilih == $kode) echo "selected"; ?>>
                        <?php echo $detail['nama']; ?> (Rp <?php echo number_format($detail['harga'], 0, ',', '.'); ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="qty">Jumlah / Durasi (Qty):</label>
            <input type="number" id="qty" name="qty" min="1" value="<?php echo $qty; ?>" required>
        </div>

        <button type="submit">Hitung Transaksi</button>
    </form>
</div>

<!-- Output Hasil Menggunakan Penghitungan Function PHP -->
<?php if ($showResult): ?>
<div class="container result-box">
    <h3>Rincian Pembayaran</h3>
    <div class="result-item">
        <span><strong>Item terpilih:</strong></span>
        <span><?php echo $namaItem; ?></span>
    </div>
    <div class="result-item">
        <span>Harga Satuan:</span>
        <span>Rp <?php echo number_format($hargaSatuan, 0, ',', '.'); ?></span>
    </div>
    <div class="result-item">
        <span>Kuantitas:</span>
        <span><?php echo $qty; ?>x</span>
    </div>
    <div class="result-item">
        <span>Total Kotor:</span>
        <span>Rp <?php echo number_format($totalKotor, 0, ',', '.'); ?></span>
    </div>
    <div class="result-item" style="color: #27ae60;">
        <span>Diskon (10% jika &ge; Rp 100.000):</span>
        <span>- Rp <?php echo number_format($diskon, 0, ',', '.'); ?></span>
    </div>
    <div class="result-item total-highlight">
        <span>Total Bersih:</span>
        <span>Rp <?php echo number_format($totalBersih, 0, ',', '.'); ?></span>
    </div>
</div>
<?php endif; ?>

</body>
</html>