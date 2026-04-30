<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::create([
            'nombre' => 'Empresa Demo',
            'cif' => 'B12345678',
            'direccion' => 'Calle Principal 123',
            'provincia' => 'Salamanca',
            'pais' => 'España',
        ]);
    }
}