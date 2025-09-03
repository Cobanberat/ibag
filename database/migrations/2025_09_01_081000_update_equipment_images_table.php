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
        Schema::table('equipment_images', function (Blueprint $table) {
            // image sütununu path olarak yeniden adlandır
            $table->renameColumn('image', 'path');
            
            // is_primary sütunu ekle
            $table->boolean('is_primary')->default(false)->after('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_images', function (Blueprint $table) {
            // path sütununu image olarak geri adlandır
            $table->renameColumn('path', 'image');
            
            // is_primary sütununu kaldır
            $table->dropColumn('is_primary');
        });
    }
};
