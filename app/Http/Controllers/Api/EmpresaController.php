<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmpresaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'cif' => 'required|string|max:20|unique:empresas,cif',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ], [
            'nombre.required' => 'El nombre de la empresa es obligatorio.',
            'cif.required' => 'El CIF de la empresa es obligatorio.',
            'cif.unique' => 'Ya existe una empresa registrada con ese CIF.',
            'email.email' => 'El correo de la empresa no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $empresa = Empresa::create([
            'nombre' => $request->nombre,
            'cif' => $request->cif,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Empresa registrada correctamente',
            'empresa' => $empresa,
        ], 201);
    }
}