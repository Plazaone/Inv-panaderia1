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
        Schema::create('orden_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedor')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumo')->onUpdate('cascade')->onDelete('cascade');
            $table->date('fechaOrdenCompra');
            $table->integer('cantidad');
            $table->double('PrecioUnidad',10,4);
            $table->double('TotalCompra',10,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_compra');
    }
};
