<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Define products array
        $products = [];
        $categoriesMap = []; // product_id => [category_ids]

        // --- SHOP 1 (Sembako) Products 1-20 ---
        $sembakoItems = [
            ['Beras Premium Pandan Wangi 5kg', 'beras-premium-pandan-wangi-5kg', 'Beras premium asli Cianjur.', 85000.00, 50, 5.00, [1]],
            ['Minyak Goreng Kelapa Sawit 2L', 'minyak-goreng-kelapa-sawit-2l', 'Minyak bening bermutu tinggi.', 38000.00, 100, 0.00, [1]],
            ['Gula Pasir Putih 1kg', 'gula-pasir-putih-1kg', 'Gula pasir tebu manis murni.', 17500.00, 120, 0.00, [1]],
            ['Tepung Terigu Segitiga Biru 1kg', 'tepung-terigu-segitiga-biru-1kg', 'Tepung terigu protein sedang.', 14000.00, 80, 0.00, [1]],
            ['Kecap Manis Bango 550ml', 'kecap-manis-bango-550ml', 'Kecap terbuat dari kedelai hitam.', 24000.00, 120, 10.00, [2]],
            ['Garam Dapur Beriodium 500g', 'garam-dapur-beriodium-500g', 'Garam dapur konsumsi sehat.', 6000.00, 150, 0.00, [2]],
            ['Merica Bubuk Ladaku 1 Renceng', 'merica-bubuk-ladaku-1-renceng', 'Merica bubuk asli berkualitas.', 18000.00, 60, 0.00, [2]],
            ['Bawang Goreng Brebes Asli 150g', 'bawang-goreng-brebes-asli-150g', 'Bawang goreng renyah wangi.', 25000.00, 40, 5.00, [2]],
            ['Teh Celup Sariwangi isi 50', 'teh-celup-sariwangi-isi-50', 'Teh hitam celup nikmat.', 12000.00, 90, 0.00, [1]],
            ['Kopi Bubuk Kapal Api 165g', 'kopi-bubuk-kapal-api-165g', 'Kopi hitam bubuk beraroma mantap.', 15000.00, 110, 0.00, [1]],
            ['Susu Kental Manis Frisian Flag 370g', 'susu-kental-manis-frisian-flag-370g', 'Susu kental manis lezat.', 14500.00, 70, 0.00, [1]],
            ['Indomie Goreng Spesial 1 Dus', 'indomie-goreng-spesial-1-dus', 'Mie instan goreng isi 40 bungkus.', 115000.00, 25, 0.00, [1]],
            ['Margarin Blue Band 200g', 'margarin-blue-band-200g', 'Margarin serbaguna untuk memasak.', 9500.00, 130, 0.00, [1, 2]],
            ['Kornet Sapi Pronas 340g', 'kornet-sapi-pronas-340g', 'Daging kornet sapi kaleng praktis.', 28500.00, 45, 0.00, [1]],
            ['Sarden ABC Saus Tomat 425g', 'sarden-abc-saus-tomat-425g', 'Ikan sarden kalengan lezat.', 22000.00, 50, 8.00, [1]],
            ['Madu Pramuka Asli 350ml', 'madu-pramuka-asli-350ml', 'Madu hutan murni berkhasiat.', 85000.00, 30, 10.00, [1]],
            ['Saos Sambal ABC Extra Pedas 335ml', 'saos-sambal-abc-extra-pedas-335ml', 'Saus cabai pedas mantap.', 16000.00, 85, 0.00, [2]],
            ['Santan Instan Kara 65ml 1 Box', 'santan-instan-kara-65ml-1-box', 'Santan kelapa cair isi 24 bungkus.', 72000.00, 20, 0.00, [1, 2]],
            ['Sirup Marjan Boudoin Coco Pandan', 'sirup-marjan-boudoin-coco-pandan', 'Sirup rasa kelapa pandan.', 23000.00, 60, 12.00, [1]],
            ['Tepung Bumbu Sasa Multiguna 200g', 'tepung-bumbu-sasa-multiguna-200g', 'Tepung bumbu praktis renyah.', 7500.00, 140, 0.00, [2]]
        ];

        $pId = 1;
        foreach ($sembakoItems as $item) {
            $products[] = [
                'id' => $pId,
                'shop_id' => 1,
                'name' => $item[0],
                'slug' => $item[1],
                'description' => $item[2],
                'image' => null,
                'price' => $item[3],
                'stock' => $item[4],
                'discount_percentage' => $item[5],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $categoriesMap[$pId] = $item[6];
            $pId++;
        }

        // --- SHOP 2 (Gadget) Products 21-35 ---
        $gadgetItems = [
            ['Powerbank Anker PowerCore 10000mAh', 'powerbank-anker-powercore-10000mah', 'Powerbank tipis pengisian cepat.', 299000.00, 30, 0.00, [3]],
            ['Kabel Data Type-C Baseus 1m', 'kabel-data-type-c-baseus-1m', 'Kabel data kuat rajutan nilon.', 49000.00, 100, 10.00, [3]],
            ['Charger Dinding Ugreen GaN 65W', 'charger-dinding-ugreen-gan-65w', 'Charger multi-port super cepat.', 350000.00, 20, 5.00, [3]],
            ['Tempered Glass King Kong iPhone 13', 'tempered-glass-king-kong-iphone-13', 'Pelindung layar antigores kuat.', 75000.00, 80, 0.00, [3]],
            ['Casing Silikon Premium iPhone 11/12/13', 'casing-silikon-premium-iphone', 'Softcase tipis antikotor.', 45000.00, 120, 15.00, [3]],
            ['Wireless Earphone TWS Lenovo LP40', 'wireless-earphone-tws-lenovo-lp40', 'TWS bluetooth suara jernih.', 129000.00, 45, 8.00, [3]],
            ['Tripod HP Mini Bluetooth', 'tripod-hp-mini-bluetooth', 'Tripod portable dilengkapi remote.', 85000.00, 55, 0.00, [3]],
            ['Ring Light LED 26cm + Stand 2m', 'ring-light-led-26cm-stand-2m', 'Lampu bundar untuk live streaming.', 149000.00, 25, 12.00, [3]],
            ['USB Flashdisk Sandisk 64GB USB 3.0', 'usb-flashdisk-sandisk-64gb-usb-30', 'Penyimpanan data cepat portable.', 89000.00, 60, 0.00, [3]],
            ['MicroSD Sandisk Ultra 128GB', 'microsd-sandisk-ultra-128gb', 'Kartu memori kamera/gadget.', 199000.00, 40, 0.00, [3]],
            ['Holder HP AC Mobil Ugreen', 'holder-hp-ac-mobil-ugreen', 'Holder HP di dashboard mobil.', 69000.00, 50, 0.00, [3]],
            ['Bluetooth Speaker Portable JBL GO 3', 'bluetooth-speaker-portable-jbl-go-3', 'Speaker mini tahan air nirkabel.', 399000.00, 15, 0.00, [3]],
            ['Kabel Aux Audio 3.5mm Ugreen 1m', 'kabel-aux-audio-35mm-ugreen-1m', 'Kabel aux jack stereo tepercaya.', 35000.00, 90, 0.00, [3]],
            ['Stylus Pen Active Universal', 'stylus-pen-active-universal', 'Pena stylus sensitif layar sentuh.', 199000.00, 35, 10.00, [3]],
            ['Mouse Wireless Logi Pebble M350', 'mouse-wireless-logi-pebble-m350', 'Mouse nirkabel tipis dan hening.', 249000.00, 28, 5.00, [3]]
        ];

        foreach ($gadgetItems as $item) {
            $products[] = [
                'id' => $pId,
                'shop_id' => 2,
                'name' => $item[0],
                'slug' => $item[1],
                'description' => $item[2],
                'image' => null,
                'price' => $item[3],
                'stock' => $item[4],
                'discount_percentage' => $item[5],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $categoriesMap[$pId] = $item[6];
            $pId++;
        }

        // --- SHOP 3 (Craft & Batik) Products 36-50 ---
        $craftItems = [
            ['Tas Anyaman Rotan Bulat Bali', 'tas-anyaman-rotan-bulat-bali', 'Tas rotan handmade khas Bali.', 135000.00, 40, 10.00, [5]],
            ['Patung Kayu Kucing Hias Set 3', 'patung-kayu-kucing-hias-set-3', 'Ukiran kayu kucing dekoratif.', 75000.00, 30, 0.00, [5, 4]],
            ['Kain Pantai Motif Mandala', 'kain-pantai-motif-mandala', 'Kain sarung pantai lembut warna-warni.', 45000.00, 150, 0.00, [5, 6]],
            ['Kain Batik Tulis Solo Kawung 2m', 'kain-batik-tulis-solo-kawung-2m', 'Batik tulis katun primisima premium.', 299000.00, 15, 0.00, [6]],
            ['Kaos Sablon Barong Bali Katun', 'kaos-sablon-barong-bali-katun', 'Kaos santai gambar Barong Bali.', 35000.00, 200, 0.00, [6]],
            ['Kipas Lipat Sutra Cendana', 'kipas-lipat-sutra-cendana', 'Kipas wangi dari kayu cendana ukir.', 65000.00, 80, 0.00, [5]],
            ['Kebaya Brokat Bali Lengan Panjang', 'kebaya-brokat-bali-lengan-panjang', 'Kebaya adat brokat halus modis.', 150000.00, 25, 8.00, [6]],
            ['Sandal Manik Khas Bali', 'sandal-manik-khas-bali', 'Sandal flat manik-manik etnik.', 55000.00, 60, 0.00, [5, 6]],
            ['Hiasan Dinding Dreamcatcher Bulu', 'hiasan-dinding-dreamcatcher-bulu', 'Gantungan dekorasi penangkap mimpi.', 48000.00, 45, 12.00, [5, 4]],
            ['Gelang Kayu Kokka Asli', 'gelang-kayu-kokka-asli', 'Gelang butir kayu kokka berkhasiat.', 30000.00, 100, 0.00, [5]],
            ['Asbak Ukir Kayu Jati', 'asbak-ukir-kayu-jati', 'Asbak dekoratif kayu jati awet.', 65000.00, 25, 0.00, [5, 4]],
            ['Kotak Tisu Anyaman Daun Pandan', 'kotak-tisu-anyaman-daun-pandan', 'Tempat tisu anyaman pandan alami.', 40000.00, 50, 5.00, [5, 4]],
            ['Ulekan Batu Kali Lereng Merapi', 'ulekan-batu-kali-lereng-merapi', 'Cobek dan ulekan batu kali asli kuat.', 125000.00, 20, 0.00, [4]],
            ['Taplak Meja Makan Tenun Ikat', 'taplak-meja-makan-tenun-ikat', 'Taplak meja makan tenun tradisional.', 18000.00, 35, 0.00, [5, 4]],
            ['Sarung Tenun Goyor Tradisional', 'sarung-tenun-goyor-tradisional', 'Sarung tenun goyor adem berkualitas.', 185000.00, 18, 10.00, [6]]
        ];

        foreach ($craftItems as $item) {
            $products[] = [
                'id' => $pId,
                'shop_id' => 3,
                'name' => $item[0],
                'slug' => $item[1],
                'description' => $item[2],
                'image' => null,
                'price' => $item[3],
                'stock' => $item[4],
                'discount_percentage' => $item[5],
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $categoriesMap[$pId] = $item[6];
            $pId++;
        }

        // Insert products into DB
        DB::table('products')->insert($products);

        // Insert category_product relations into DB
        $pivotEntries = [];
        foreach ($categoriesMap as $productId => $catIds) {
            foreach ($catIds as $catId) {
                $pivotEntries[] = [
                    'category_id' => $catId,
                    'product_id' => $productId,
                ];
            }
        }
        DB::table('category_product')->insert($pivotEntries);

        // --- PRODUCT VARIANTS ---
        DB::table('product_variants')->insert([
            // Beras (Product 1)
            ['product_id' => 1, 'name' => 'Kemasan 5kg', 'price' => 85000.00, 'stock' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 1, 'name' => 'Kemasan 10kg', 'price' => 165000.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()],
            // Powerbank (Product 21)
            ['product_id' => 21, 'name' => 'Warna Hitam', 'price' => 299000.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 21, 'name' => 'Warna Putih', 'price' => 299000.00, 'stock' => 15, 'created_at' => now(), 'updated_at' => now()],
            // Casing Silikon (Product 25)
            ['product_id' => 25, 'name' => 'Tipe iPhone 11', 'price' => 45000.00, 'stock' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 25, 'name' => 'Tipe iPhone 12', 'price' => 45000.00, 'stock' => 40, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 25, 'name' => 'Tipe iPhone 13', 'price' => 48000.00, 'stock' => 40, 'created_at' => now(), 'updated_at' => now()],
            // Sandal Manik (Product 43)
            ['product_id' => 43, 'name' => 'Ukuran 38', 'price' => 55000.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 43, 'name' => 'Ukuran 39', 'price' => 55000.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['product_id' => 43, 'name' => 'Ukuran 40', 'price' => 55000.00, 'stock' => 20, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}
