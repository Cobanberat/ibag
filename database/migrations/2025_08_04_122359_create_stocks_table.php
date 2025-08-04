<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    public function up()
    {
       Schema::create('stocks', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('equipment_id');
    $table->integer('stock_quantity')->default(0);
    $table->timestamps();

    $table->foreign('equipment_id')->references('id')->on('equipments')->cascadeOnDelete();
});

    }

    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}

