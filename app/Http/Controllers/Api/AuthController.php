<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'nombre' => 'required|string|max:100',
        'dni' => 'required|string|max:10|unique:users,dni',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'cif' => 'required|string|exists:empresas,cif',
    ], [
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre no puede superar los 100 caracteres.',

        'dni.required' => 'El DNI es obligatorio.',
        'dni.max' => 'El DNI no puede superar los 10 caracteres.',
        'dni.unique' => 'Ya existe un usuario registrado con ese DNI.',

        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email no tiene un formato válido.',
        'email.unique' => 'Ya existe un usuario registrado con ese email.',

        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 6 caracteres.',

        'cif.required' => 'El CIF de la empresa es obligatorio.',
        'cif.exists' => 'No existe ninguna empresa registrada con ese CIF.',
    ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $empresa = Empresa::where('cif', $request->cif)->firstOrFail();

        $user = User::create([
            'id_empresa' => $empresa->id_empresa,
            'nombre' => $request->nombre,
            'dni' => $request->dni,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'token' => $token,
            'user' => $user,
            'empresa' => $empresa,
        ], 201);
    }

    public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|string',
    ], [
        'email.required' => 'El email es obligatorio.',
        'email.email' => 'El email no tiene un formato válido.',
        'password.required' => 'La contraseña es obligatoria.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json([
            'message' => 'No existe ningún usuario registrado con ese email.',
        ], 404);
    }

    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'message' => 'La contraseña introducida no es correcta.',
        ], 401);
    }

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json([
        'message' => 'Login correcto',
        'token' => $token,
        'user' => $user,
        'empresa' => $user->empresa,
    ]);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente',
        ]);
    }

public function me(Request $request)
{
    $usuario = $request->user();

    $empresa = \App\Models\Empresa::where('id_empresa', $usuario->id_empresa)->first();

    return response()->json([
        'usuario' => [
            'id' => $usuario->id,
            'nombre' => $usuario->nombre,
            'dni' => $usuario->dni,
            'email' => $usuario->email,
            'id_empresa' => $usuario->id_empresa,
            'empresa' => $empresa ? $empresa->nombre : 'No indicada',
        ],
    ]);
}
public function changePassword(Request $request)
{
    $validator = Validator::make($request->all(), [
        'password_actual' => 'required|string',
        'password_nueva' => 'required|string|min:6|confirmed',
    ], [
        'password_actual.required' => 'Debes introducir tu contraseña actual.',
        'password_nueva.required' => 'Debes introducir una nueva contraseña.',
        'password_nueva.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
        'password_nueva.confirmed' => 'La confirmación de la contraseña no coincide.',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
        ], 422);
    }

    $usuario = $request->user();

    if (!Hash::check($request->password_actual, $usuario->password)) {
        return response()->json([
            'message' => 'La contraseña actual no es correcta.',
        ], 422);
    }

    $usuario->password = Hash::make($request->password_nueva);
    $usuario->save();

    return response()->json([
        'message' => 'Contraseña actualizada correctamente.',
    ]);
}
}