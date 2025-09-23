<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQL Server, we need to drop and recreate the constraint
        if (DB::getDriverName() === 'sqlsrv') {
            // First, update any existing 'user' values to 'üye'
            DB::table('users')->where('role', 'user')->update(['role' => 'üye']);
            
            // Drop the existing constraint
            DB::statement("ALTER TABLE users DROP CONSTRAINT CK__users__role__4FD1D5C8");
            
            // Add the new constraint with the correct values
            DB::statement("ALTER TABLE users ADD CONSTRAINT CK__users__role__4FD1D5C8 CHECK (role IN ('admin', 'ekip_yetkilisi', 'üye'))");
        } else {
            // For other databases, use the standard Laravel approach
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['admin', 'ekip_yetkilisi', 'üye'])->default('üye')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlsrv') {
            // Update 'üye' values back to 'user'
            DB::table('users')->where('role', 'üye')->update(['role' => 'user']);
            
            // Drop the new constraint
            DB::statement("ALTER TABLE users DROP CONSTRAINT CK__users__role__4FD1D5C8");
            
            // Add the old constraint
            DB::statement("ALTER TABLE users ADD CONSTRAINT CK__users__role__4FD1D5C8 CHECK (role IN ('user', 'admin'))");
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('role', ['user', 'admin'])->default('user')->change();
            });
        }
    }
};
