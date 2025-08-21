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
        Schema::create('faults', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_stock_id')->constrained('stock_depo')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['arıza', 'bakım', 'diğer'])->default('arıza');
            $table->enum('priority', ['normal', 'yüksek', 'acil'])->default('normal');
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->enum('status', ['beklemede', 'işlemde', 'giderildi', 'iptal'])->default('beklemede');
            $table->date('reported_date');
            $table->date('resolved_date')->nullable();
            $table->text('resolution_note')->nullable();
            $table->string('resolved_photo_path')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('no action');
            $table->timestamps();
            
            $table->index(['equipment_stock_id', 'status']);
            $table->index(['type', 'priority']);
            $table->index('reported_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faults');
    }
};
