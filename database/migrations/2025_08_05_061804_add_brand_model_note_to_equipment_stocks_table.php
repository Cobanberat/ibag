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
        Schema::table('equipment_stocks', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('equipment_id');
            $table->string('model')->nullable()->after('brand');
            $table->text('note')->nullable()->after('last_used_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_stocks', function (Blueprint $table) {
            $table->dropColumn(['brand', 'model', 'note']);
        });
    }
};
