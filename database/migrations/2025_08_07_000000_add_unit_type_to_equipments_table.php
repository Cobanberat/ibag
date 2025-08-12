<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->enum('unit_type', ['adet', 'metre', 'kilogram', 'litre', 'paket', 'kutu', 'çift', 'takım'])->default('adet')->after('critical_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropColumn('unit_type');
        });
    }
};
