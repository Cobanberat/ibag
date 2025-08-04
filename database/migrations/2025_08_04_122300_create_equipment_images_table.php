<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentImagesTable extends Migration
{
    public function up()
    {
       Schema::create('equipment_images', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipment_id');
    $table->string('image');
    $table->timestamps();

    $table->foreign('equipment_id')->references('id')->on('equipments')->cascadeOnDelete();
});

    }

    public function down()
    {
        Schema::dropIfExists('equipment_images');
    }
}
