<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class AlmacenController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $almacenes = Almacen::where('id_empresa', $user->id_empresa)
            ->orderBy('id_almacen', 'desc')
            ->get();

        return response()->json($almacenes);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'direccion' => 'required|string|max:150',
            'provincia' => 'required|string|max:50',
            'pais' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $almacen = Almacen::create([
            'id_empresa' => $request->user()->id_empresa,
            'direccion' => $request->direccion,
            'provincia' => $request->provincia,
            'pais' => $request->pais,
        ]);

        return response()->json([
            'message' => 'Almacén creado correctamente',
            'almacen' => $almacen,
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $almacen = Almacen::where('id_almacen', $id)
            ->where('id_empresa', $user->id_empresa) // 🔧 CORREGIDO
            ->first();

        if (!$almacen) {
            return response()->json([
                'message' => 'Almacén no encontrado'
            ], 404);
        }

        $validated = $request->validate([
            'direccion' => 'required|string|max:255',
            'provincia' => 'required|string|max:100',
            'pais' => 'required|string|max:100',
        ]);

        $almacen->update($validated);

        return response()->json($almacen, 200);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        $user = $request->user();

        $almacen = Almacen::where('id_almacen', $id)
            ->where('id_empresa', $user->id_empresa) // 🔧 CORREGIDO
            ->first();

        if (!$almacen) {
            return response()->json([
                'message' => 'Almacén no encontrado'
            ], 404);
        }

        $almacen->delete();

        return response()->json([
            'message' => 'Almacén eliminado correctamente'
        ], 200);
    }
}