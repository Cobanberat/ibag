<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentCategoriesTable extends Migration
{
    public function up()
    {
      Schema::create('equipment_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->unsignedBigInteger('parent_id')->nullable();
    $table->text('description')->nullable();
    $table->timestamps();

    $table->foreign('parent_id')->references('id')->on('equipment_categories')->nullOnDelete();
});

    }

    public function down()
    {
        Schema::dropIfExists('equipment_categories');
    }
}

