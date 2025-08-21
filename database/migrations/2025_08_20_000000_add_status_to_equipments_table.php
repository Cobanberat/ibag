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
            $table->enum('status', ['active', 'maintenance', 'faulty', 'inactive'])->default('active')->after('unit_type');
            $table->text('status_note')->nullable()->after('status');
            $table->unsignedBigInteger('responsible_user_id')->nullable()->after('status_note');
            $table->foreign('responsible_user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipments', function (Blueprint $table) {
            $table->dropForeign(['responsible_user_id']);
            $table->dropColumn(['status', 'status_note', 'responsible_user_id']);
        });
    }
};
