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
        Schema::create('proveedor', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre1',20);
            $table->string('Nombre2',20)->nullable();
            $table->string('Apellido1',20);
            $table->string('Apellido2',20);
            $table->string('Email')->unique();
            $table->string('Telefono',12);
            $table->string('Direccion',25);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedor');
    }
};
