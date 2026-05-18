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
        'telefono' => 'required|string|max:11',
        'email' => 'required|email|max:50',
        'direccion' => 'required|string|max:100',
        'provincia' => 'required|string|max:50',
        'pais' => 'required|string|max:50',
    ], [
        'nombre.required' => 'El nombre de la empresa es obligatorio.',
        'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

        'cif.required' => 'El CIF de la empresa es obligatorio.',
        'cif.max' => 'El CIF no puede superar los 20 caracteres.',
        'cif.unique' => 'Ya existe una empresa registrada con ese CIF.',

        'telefono.required' => 'El numero de la empresa es obligatorio.',
        'telefono.max' => 'El teléfono no puede superar los 11 caracteres.',

        'email.required' => 'El correo de la empresa es obligatorio.',
        'email.email' => 'El email de la empresa no tiene un formato válido.',
        'email.max' => 'El email no puede superar los 50 caracteres.',

        'direccion.required' => 'La dirección de la empresa es obligatoria.',
        'direccion.max' => 'La dirección no puede superar los 100 caracteres.',

        'provincia.required' => 'La provincia es obligatoria.',
        'provincia.max' => 'La provincia no puede superar los 50 caracteres.',

        'pais.required' => 'El país es obligatorio.',
        'pais.max' => 'El país no puede superar los 50 caracteres.',
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