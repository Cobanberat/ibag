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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('email');
            $table->enum('role', ['admin', 'ekip_yetkilisi', 'üye'])->default('üye')->after('username');
            $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
            $table->string('avatar_color')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable()->after('avatar_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'status', 'avatar_color', 'last_login_at']);
        });
    }
};
