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
        Schema::rename('equipment_stocks', 'stock_depo');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('stock_depo', 'equipment_stocks');
    }
};
