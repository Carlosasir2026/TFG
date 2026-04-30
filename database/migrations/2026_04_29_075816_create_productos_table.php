<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id('id_producto');
            $table->unsignedBigInteger('id_almacen');
            $table->string('nombre', 100);
            $table->integer('cantidad');
            $table->decimal('precio', 10, 2);
            $table->integer('stock_minimo');
            $table->string('codigo_barras', 50)->unique();
            $table->timestamps();

            $table->foreign('id_almacen')
                ->references('id_almacen')
                ->on('almacenes')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};