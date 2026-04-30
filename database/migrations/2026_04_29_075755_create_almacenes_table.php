<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('almacenes', function (Blueprint $table) {
            $table->id('id_almacen');
            $table->unsignedBigInteger('id_empresa');
            $table->string('direccion', 150);
            $table->string('provincia', 50);
            $table->string('pais', 50);
            $table->timestamps();

            $table->foreign('id_empresa')
                ->references('id_empresa')
                ->on('empresas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('almacenes');
    }
};