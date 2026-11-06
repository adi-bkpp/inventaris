<?php

// buat function konversiGrade yang menerima nilai
function konversiGrade($nilai) {
    // kondisi
    if ($nilai < 0 || $nilai > 100) {
        return "Nilai tidak valid! Masukkan nilai 0-100.";
    } elseif ($nilai >= 85) {
        return "Grade: A";
    } elseif ($nilai >= 70) {
        return "Grade: B";
    } elseif ($nilai >= 55) {
        return "Grade: C";
    } else {
        return "Grade: D";
    }

}

// inisialisasi nilai
$nilai = 101;

// tampilkan dan panggil function
echo konversiGrade($nilai);

// buat function hitung diskon yang menerima totalBelanja
function hitungDiskon($totalBelanja) {
    // kondisi
    if ($totalBelanja >= 500000) {
        $diskon = 10;
    } elseif ($totalBelanja >= 200000) {
        $diskon = 5;
    } else {
        $diskon = 0;
    }

    // hitung potongan dan total akhir, kemudian simpan kedalam variabel
    $potongan = $totalBelanja * $diskon / 100;
    $totalAkhir = $totalBelanja - $potongan;

    // tampilkan diskon, potongan dan total akhir dalam aray
    return [
        'diskon' => $diskon,
        'potongan' => $potongan,
        'totalAkhir' => $totalAkhir
    ];
}

// inisialisasi total belanja
$totalBelanja = 600000;

// simpan hasil diskon menjadi variabel
$hasil = hitungDiskon($totalBelanja);

// tampilkan total belanja, diskon, potongan dan total akhir
echo "\n\nTotal Belanja: Rp" . number_format($totalBelanja, 0, ',', '.') . "\n";
echo "Diskon: " . $hasil['diskon'] . " %\n";
echo "Potongan: Rp" . number_format($hasil['potongan'], 0, ',', '.') . "\n";
echo "Total Akhir: Rp" . number_format($hasil['totalAkhir'], 0, ',', '.');

// buat function cari nilai terbesar yang menerima angka
function cariNilaiTerbesar(array $angka) {
    if ($angka === []) {
        throw new InvalidArgumentException('Daftar angka tidak boleh kosong.');
    }

    $terbesar = $angka[0];

    // buat perulangan untuk menelusuri angka
    foreach ($angka as $a) {
        // kondisi jika a > terbesar, maka ubah nilai variabel terbesar menjadi a
        if ($a > $terbesar) {
            $terbesar = $a;
        }
    }

    // tampilkan angka terbesar
    return $terbesar;
}

// aray data yang akan diproses di function cariNilaiTerbesar
$data = [7, 21, 3, 15];
// $data = [-4, -9, -2];

echo "\n\nNilai terbesar adalah: " . cariNilaiTerbesar($data);