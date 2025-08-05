<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use App\Models\EquipmentCategory;
use App\Models\EquipmentStock;

class EquipmentStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Önce kategorileri oluştur
        $categories = [
            ['name' => 'Jeneratörler'],
            ['name' => 'Aletler'],
            ['name' => 'Ekipmanlar']
        ];

        foreach ($categories as $category) {
            EquipmentCategory::firstOrCreate($category);
        }

        // Ekipmanları oluştur
        $equipments = [
            ['name' => '2.5 KW Benzinli Jeneratör', 'category_id' => 1],
            ['name' => '3.5 KW Benzinli Jeneratör', 'category_id' => 1],
            ['name' => '4.4 KW Benzinli Jeneratör', 'category_id' => 1],
            ['name' => '7.5 KW Dizel Jeneratör', 'category_id' => 1],
            ['name' => 'Matkap', 'category_id' => 2],
            ['name' => 'Çekiç', 'category_id' => 2],
            ['name' => 'Tornavida Seti', 'category_id' => 2],
            ['name' => 'Kablo', 'category_id' => 3],
            ['name' => 'Ampul', 'category_id' => 3],
        ];

        foreach ($equipments as $equipment) {
            Equipment::firstOrCreate($equipment);
        }

        // Ekipman stoklarını oluştur
        $stocks = [
            [
                'equipment_id' => 1,
                'brand' => 'Honda',
                'model' => 'EU22i',
                'status' => 'Sıfır',
                'code' => 'GEN001',
                'location' => 'Depo A',
                'quantity' => 5,
                'feature' => 'Sessiz çalışma, yakıt tasarrufu',
                'size' => '2.5 KW',
                'note' => 'Yeni alınan ekipman'
            ],
            [
                'equipment_id' => 2,
                'brand' => 'Yamaha',
                'model' => 'EF3000iSE',
                'status' => 'Açık',
                'code' => 'GEN002',
                'location' => 'Depo B',
                'quantity' => 3,
                'feature' => 'Elektrikli start, uzaktan kumanda',
                'size' => '3.5 KW',
                'note' => 'Kullanımda olan ekipman'
            ],
            [
                'equipment_id' => 3,
                'brand' => 'Kipor',
                'model' => 'IG4400',
                'status' => 'Sıfır',
                'code' => 'GEN003',
                'location' => 'Depo A',
                'quantity' => 2,
                'feature' => 'Dijital panel, çoklu çıkış',
                'size' => '4.4 KW',
                'note' => 'Rezerv ekipman'
            ],
            [
                'equipment_id' => 4,
                'brand' => 'Cummins',
                'model' => 'C7.5D',
                'status' => 'Açık',
                'code' => 'GEN004',
                'location' => 'Depo C',
                'quantity' => 1,
                'feature' => 'Endüstriyel kullanım, uzun ömür',
                'size' => '7.5 KW',
                'note' => 'Ana güç kaynağı'
            ],
            [
                'equipment_id' => 5,
                'brand' => 'Bosch',
                'model' => 'GBH 2-26',
                'status' => 'Sıfır',
                'code' => 'TOOL001',
                'location' => 'Depo B',
                'quantity' => 8,
                'feature' => 'Kırıcı-delici, SDS-plus',
                'size' => '800W',
                'note' => 'İnşaat ekipmanı'
            ],
            [
                'equipment_id' => 6,
                'brand' => 'Stanley',
                'model' => 'FatMax',
                'status' => 'Açık',
                'code' => 'TOOL002',
                'location' => 'Depo A',
                'quantity' => 12,
                'feature' => 'Çelik saplı, ergonomik',
                'size' => '500g',
                'note' => 'Genel kullanım'
            ],
            [
                'equipment_id' => 7,
                'brand' => 'Wera',
                'model' => 'Kraftform',
                'status' => 'Sıfır',
                'code' => 'TOOL003',
                'location' => 'Depo B',
                'quantity' => 6,
                'feature' => 'Profesyonel set, 25 parça',
                'size' => 'Set',
                'note' => 'Teknik servis ekipmanı'
            ],
            [
                'equipment_id' => 8,
                'brand' => 'Viko',
                'model' => 'Kablo',
                'status' => 'Açık',
                'code' => 'CABLE001',
                'location' => 'Depo C',
                'quantity' => 50,
                'feature' => '3x2.5mm, NYM kablo',
                'size' => '100m',
                'note' => 'Elektrik tesisatı'
            ],
            [
                'equipment_id' => 9,
                'brand' => 'Philips',
                'model' => 'LED',
                'status' => 'Sıfır',
                'code' => 'LIGHT001',
                'location' => 'Depo A',
                'quantity' => 100,
                'feature' => 'Enerji tasarruflu, 9W',
                'size' => 'E27',
                'note' => 'Aydınlatma ekipmanı'
            ]
        ];

        foreach ($stocks as $stock) {
            EquipmentStock::firstOrCreate(['code' => $stock['code']], $stock);
        }
    }
} 