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
        'telefono' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:100',
        'direccion' => 'required|string|max:150',
        'provincia' => 'required|string|max:50',
        'pais' => 'required|string|max:50',
    ], [
        'nombre.required' => 'El nombre de la empresa es obligatorio.',
        'cif.required' => 'El CIF de la empresa es obligatorio.',
        'cif.unique' => 'Ya existe una empresa registrada con ese CIF.',
        'email.email' => 'El email de la empresa no tiene un formato válido.',
        'direccion.required' => 'La dirección de la empresa es obligatoria.',
        'provincia.required' => 'La provincia de la empresa es obligatoria.',
        'pais.required' => 'El país de la empresa es obligatorio.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $empresa = Empresa::create($validator->validated());

    return response()->json([
        'message' => 'Empresa registrada correctamente',
        'empresa' => $empresa,
    ], 201);
}
}