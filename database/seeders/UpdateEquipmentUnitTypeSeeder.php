<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Equipment;
use Illuminate\Support\Facades\DB;

class UpdateEquipmentUnitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // unit_type alanı null olan tüm ekipmanları 'adet' olarak güncelle
        $updatedCount = Equipment::whereNull('unit_type')
            ->orWhere('unit_type', '')
            ->update(['unit_type' => 'adet']);

        $this->command->info("{$updatedCount} ekipmanın unit_type alanı 'adet' olarak güncellendi.");

        // Tüm ekipmanların unit_type alanının dolu olduğunu kontrol et
        $totalEquipments = Equipment::count();
        $equipmentsWithUnitType = Equipment::whereNotNull('unit_type')
            ->where('unit_type', '!=', '')
            ->count();

        $this->command->info("Toplam ekipman: {$totalEquipments}");
        $this->command->info("Unit type alanı dolu olan: {$equipmentsWithUnitType}");
    }
}
