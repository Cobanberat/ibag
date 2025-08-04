<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentStocksTable extends Migration
{
    public function up()
    {
Schema::create('equipment_stocks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('equipment_id')->constrained('equipments')->onDelete('cascade');
    $table->string('status')->default('aktif');
    $table->string('code')->unique();
    $table->string('location');
    $table->integer('quantity')->default(1);
    $table->text('feature')->nullable();
    $table->string('size')->nullable();
    $table->timestamp('status_updated_at')->nullable();
    $table->timestamp('last_used_at')->nullable();
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('equipment_stocks');
    }
}

