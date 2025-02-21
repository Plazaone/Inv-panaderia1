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
        Schema::create('fabricacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insumo_id')->constrained('insumo')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('producto')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('CantidadProducto');
            $table->time('TiempoFabricacion');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fabricacion');
    }
};
