<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductoController extends Controller
{
    public function index(Request $request, $id)
    {
        $almacen = Almacen::where('id_almacen', $id)
            ->where('id_empresa', $request->user()->id_empresa)
            ->firstOrFail();

        return response()->json($almacen->productos);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'nombre' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'codigo_barras' => 'required|string|max:50|unique:productos,codigo_barras',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $almacen = Almacen::where('id_almacen', $request->id_almacen)
            ->where('id_empresa', $request->user()->id_empresa)
            ->firstOrFail();

        $producto = Producto::create([
            'id_almacen' => $almacen->id_almacen,
            'nombre' => $request->nombre,
            'cantidad' => $request->cantidad,
            'precio' => $request->precio,
            'stock_minimo' => $request->stock_minimo,
            'codigo_barras' => $request->codigo_barras,
        ]);

        return response()->json([
            'message' => 'Producto creado correctamente',
            'producto' => $producto,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $almacen = Almacen::where('id_almacen', $producto->id_almacen)
            ->where('id_empresa', $request->user()->id_empresa)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'cantidad' => 'required|integer|min:0',
            'precio' => 'required|numeric|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'codigo_barras' => 'required|string|max:50|unique:productos,codigo_barras,' . $id . ',id_producto',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $producto->update($request->only([
            'nombre',
            'cantidad',
            'precio',
            'stock_minimo',
            'codigo_barras',
        ]));

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'producto' => $producto,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        Almacen::where('id_almacen', $producto->id_almacen)
            ->where('id_empresa', $request->user()->id_empresa)
            ->firstOrFail();

        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado correctamente',
        ]);
    }
}