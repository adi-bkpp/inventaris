<?php
$barang = [
['nama' => 'Mouse USB', 'stok' => 12, 'harga' => 75000, 'kategori' => 'Elektronik'],
['nama' => 'Kertas A4', 'stok' => 40, 'harga' => 55000, 'kategori' => 'ATK'],
['nama' => 'Spidol', 'stok' => 3, 'harga' => 12000, 'kategori' => 'ATK'],
['nama' => 'Flashdisk', 'stok' => 2, 'harga' => 90000, 'kategori' => 'Elektronik'],
];
$totalNilai = 0;
foreach ($barang as $b) {
$totalNilai += $b['stok'] * $b['harga'];
}
echo "Total: Rp "
. number_format($totalNilai, 0, ',', '.');

// 1. saring: hanya stok 5 ke bawah
$hampirHabis = array_filter(
$barang,
fn ($b) => $b['stok'] <= 5
);
echo "\nHampir habis:\n";
foreach ($hampirHabis as $b) {
echo "- {$b['nama']} (sisa {$b['stok']})\n";
}
// 2. urutkan: harga termahal di atas
usort($barang,
fn ($a, $b) => $b['harga'] <=> $a['harga']
);

function hitungDiskon(float $harga, float $persen): float {
    $diskon = $harga * $persen / 100;
    $hitung = $persen >= 0 && $persen <= 100 ? 
        $harga - $diskon : $harga;
    
    return $hitung;

    // bisa juga pakai if else
    // if ($persen >= 0 && $persen <= 100) {
    //     $diskon = $harga * $persen / 100;
    //     return $harga - $diskon;
    // } else {
    //     return $harga;
    // }
}

echo "\nFunction hitungDiskon\n";
foreach ($barang as $b) {
    $hargaDiskon = hitungDiskon($b['harga'], 50);

    echo "{$b['nama']} : Rp "
        . number_format($hargaDiskon, 0, ',', '.')
        . "\n";
}
echo "\n";
echo "Latihan terbimbing ".hitungDiskon(100000, 10);
echo "\nLatihan terbimbing ".hitungDiskon(100000, 150);
echo "\n";

function cariBarang($barang, $kataKunci) {
    return array_filter(
        $barang,
        fn ($b) => str_contains(
            strtolower($b['nama']),
            strtolower($kataKunci)
        )
    );
}

$hasil = cariBarang($barang, 'mouse');

echo "\nFunction cariBarang\n";
foreach ($hasil as $b) {
    echo "- {$b['nama']} | Stok: {$b['stok']} | Harga: Rp "
        . number_format($b['harga'], 0, ',', '.')
        . "\n";
}

function stokPerKategori($barang) {
    $hasil = [];

    foreach ($barang as $b) {
        $kategori = $b['kategori'];

        if (!isset($hasil[$kategori])) {
            $hasil[$kategori] = 0;
        }

        $hasil[$kategori] += $b['stok'];
    }

    return $hasil;
}

$stok = stokPerKategori($barang);

echo "\nTotal stok per kategori:\n";

foreach ($stok as $kategori => $total) {
    echo "- $kategori: $total\n";
}