<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('assignment_items', function (Blueprint $table) {
        $table->string('return_photo_path')->nullable()->after('photo_path');
    });
}

public function down()
{
    Schema::table('assignment_items', function (Blueprint $table) {
        $table->dropColumn('return_photo_path');
    });
}

};
