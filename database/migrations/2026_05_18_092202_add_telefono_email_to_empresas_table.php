<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'telefono')) {
                $table->string('telefono', 20)->nullable()->after('cif');
            }

            if (!Schema::hasColumn('empresas', 'email')) {
                $table->string('email', 100)->nullable()->after('telefono');
            }
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            if (Schema::hasColumn('empresas', 'email')) {
                $table->dropColumn('email');
            }

            if (Schema::hasColumn('empresas', 'telefono')) {
                $table->dropColumn('telefono');
            }
        });
    }
};