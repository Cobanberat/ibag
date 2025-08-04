<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentsTable extends Migration
{
    public function up()
    {
       Schema::create('equipments', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->unsignedBigInteger('category_id');
    $table->timestamps();
    $table->foreign('category_id')->references('id')->on('equipment_categories')->cascadeOnDelete();
});
    }

    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
