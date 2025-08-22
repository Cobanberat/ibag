<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assignment_items', function (Blueprint $table) {
            if (Schema::hasColumn('assignment_items', 'quantity')) {
                $table->dropColumn('quantity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('assignment_items', function (Blueprint $table) {
            if (!Schema::hasColumn('assignment_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
        });
    }
};
