<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('id_user');
            $table->unsignedBigInteger('id_empresa');
            $table->string('nombre', 100);
            $table->string('dni', 20)->unique();
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->string('rol', 100)->default('usuario');
            $table->timestamps();

            $table->foreign('id_empresa')
                ->references('id_empresa')
                ->on('empresas')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};