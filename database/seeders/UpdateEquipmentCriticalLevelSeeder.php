<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Equipment;

class UpdateEquipmentCriticalLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tüm equipment'lere critical_level değeri atayalım
        Equipment::whereNull('critical_level')->update(['critical_level' => 3]);
        
        // Bazı equipment'lere farklı critical_level değerleri verelim
        Equipment::where('name', 'like', '%jeneratör%')->update(['critical_level' => 2]);
        Equipment::where('name', 'like', '%bilgisayar%')->update(['critical_level' => 5]);
        Equipment::where('name', 'like', '%yazıcı%')->update(['critical_level' => 2]);
        
        echo "Equipment critical_level değerleri güncellendi.\n";
    }
}
