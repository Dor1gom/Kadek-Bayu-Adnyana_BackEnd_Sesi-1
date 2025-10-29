<?php
// Array produk sesuai data yang diberikan
$produk = [
    ["kode" => "C001", "nama" => "Mie Sedap Goreng", "Harga" => 3200, "stok" => "200"],
    ["kode" => "C002", "nama" => "Teh Pucuk", "Harga" => 4000, "stok" => "150"],
    ["kode" => "C003", "nama" => "Susu Ultramilk", "Harga" => 6200, "stok" => "60"],
    ["kode" => "C004", "nama" => "Tango", "Harga" => 12000, "stok" => "40"],
    ["kode" => "C005", "nama" => "Beras Tawon 5kg", "Harga" => 80000, "stok" => "30"]
];

// Fungsi untuk mencari produk berdasarkan kode 
function cariProduk($array_produk, $kode){
    foreach ($array_produk as $item){
        if ($item['kode'] === $kode){
            return $item;
        }
    }
    return null;
}
//Fungsi untuk menghitung subtotal belanjanya (harga * jumlah)  
function hitungSubtotal($harga, $jumlah){
    return $harga * $jumlah;
}
//Fungsi untuk menghitung diskon berdasarkan total belanjanya
function hitungDiskon($total){
    if ($total >= 200000){
        return $total * 0.20;
    }else if ($total >= 100000){
        return $total * 0.10;
    }else{
        return 0;
    }
} 

//Fungsi menghitung pajak PPN dengan default 10%
function hitungPajak($total, $persen = 10){
    return $total * ($persen / 100);
}

//Fungsi untuk mengurangi stok produk (pass by reference)
function kurangiStok(&$produk, $jumlah){
    $produk['stok'] -= $jumlah;
}

//Fungsi untuk format Rupiah
function formatRupiah($angka){
    return "Rp " . number_format($angka, 0, ',', '.');
} 

//Fungsi untuk membuat dan menampilkan struk belanja
function buatStrukBelanja($transaksi, &$array_produk){
    $subtotal = 0;
    $tanggal = date("d F Y");

    echo "============================================\n";
    echo "              TOKO KOKOKO\n";
    echo "============================================\n";
    echo "Tanggal: $tanggal\n\n";


//Loop untuk menampilkan detail transaksi 
foreach ($transaksi as $item){
    $produk_ditemukan = cariProduk($array_produk, $item['kode']);
    if ($produk_ditemukan){
        $subtotal_item = hitungSubtotal($produk_ditemukan['harga'], $item['jumlah']);
        $subtotal += $subtotal_item;
        echo $produk_ditemukan['nama'] . "\n";
        echo formatRupiah($produk_ditemukan['harga'] . " x " . $item['jumlah'] . "        =" . formatRupiah($subtotal_item) . "\n\n");
        //Kurangi stok
        kurangiStok($produk_ditemukan, $item['jumlah']);
    }
}

//Hitung diskon
$diskon = hitungDiskon($subtotal);
$subtotal_setelah_diskon = $subtotal - $diskon;

//Hitung pajak 
$pajak = hitungPajak($subtotal_setelah_diskon);

//Total bayar
$total_bayar = $subtotal_setelah_diskon + $pajak;

echo "--------------------------------------------\n";
echo "Subtotal                 =" . formatRupiah($subtotal) . "\n";
echo "Diskon (" . (int)(($diskon / $subtotal) * 100) . "%)              =" . formatRupiah($diskon) . "\n";
echo "Subtotal setelah diskon  =" . formatRupiah($subtotal_setelah_diskon) . "\n";
echo "PPN (10%)                =" . formatRupiah($pajak) . "\n";
echo "--------------------------------------------\n";
echo "TOTAL BAYAR              =" . formatRupiah($total_bayar) . "\n";
echo "============================================\n";

//Tampilkan status stok setelah transaksi
echo "Status stok setelah transaksi: \n";
foreach ($transaksi as $item){
    $produk_ditemukan = cariProduk($array_produk, $item['kode']);
    if ($produk_ditemukan){
        echo "- " . $produk_ditemukan['nama'] . ": " . $produk_ditemukan['stok'] . " pcs\n";
    }
}
echo "============================================\n";
echo "    Terima Kasih Atas Kunjungan Anda\n";
echo "============================================\n";
}

//Contoh transaksi
$transaksi = [
    ["kode", "nama" => "C004", "jumlah" => 7], // Tango
    ["kode", "nama" => "C003", "jumlah" => 8],// Susu Ultramilk
    ["kode", "nama" => "C005", "jumlah" => 6] // Beras Tawon 5kg
];

//Jalankan fungsi untuk membuat struk 
buatStrukBelanja($transaksi, $produk);
?>
