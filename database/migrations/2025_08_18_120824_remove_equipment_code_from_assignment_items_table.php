<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_items', function (Blueprint $table) {
            if (Schema::hasColumn('assignment_items', 'equipment_code')) {
                $table->dropColumn('equipment_code');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assignment_items', function (Blueprint $table) {
            if (!Schema::hasColumn('assignment_items', 'equipment_code')) {
                $table->string('equipment_code');
            }
        });
    }
};

