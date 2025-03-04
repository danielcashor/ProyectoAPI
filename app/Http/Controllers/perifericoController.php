<?php

namespace App\Http\Controllers;
use App\Models\Periferico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class perifericoController extends Controller
{
    public function index(Request $request)
{
    $precio = $request->query('precio');
    
    if ($precio !== null && is_numeric($precio)) {
        $perifericos = Periferico::where('Precio', '<=', (int)$precio)->get();
        
        if ($perifericos->isEmpty()) {
            return response()->json([
                'message' => 'No se encontraron periféricos con el precio especificado',
                'status' => 404
            ], 404);
        }
    } else {
        $perifericos = Periferico::all();
    }

    return response()->json([
        'perifericos' => $perifericos,
        'status' => 200
    ], 200);
}

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            "Nombre" => "required|max:50|string",
            "Descripcion" => "required|string",
            "Precio" => "required|integer"
        ]);
        if($validator->fails()){
            return response()->json(['message' => 'Error en la validacion de los datos','errors' => $validator->errors()]);
        }
    
        $perifericoExistente = Periferico::where('Nombre', $request->Nombre)->first();
    
        if ($perifericoExistente) {
            return response()->json(['message' => 'El periférico ya existe', 'periferico' => $perifericoExistente], 200);
        }
    
        $periferico = Periferico::create([
            "Nombre" => $request->Nombre,
            "Descripcion" => $request->Descripcion,
            "Precio" => $request->Precio
        ]);
    
        if(!$periferico){
            return response()->json(['message' => 'Error al crear el periférico']);
        }
    
        return response()->json(['periferico' => $periferico], 201);
    }
    
    public function show($id){
        $periferico = Periferico::find($id);
        if(!$periferico){
            $data = [
                'message' => 'Periferico no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $data = [
            'periferico' => $periferico,
            'status' => 200
        ];

        return response()->json($data,200);

    }

    public function borrar($id){
        $periferico = Periferico::find($id);

        if(!$periferico){
            $data = [
                'message' => 'Periferico no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }

        $periferico->delete();

        $data = [
            'message' => 'Periferico eliminado',
            'status' => 200
        ];

        return response()->json($data,200);

    }

    public function update(Request $request, $id){
        $periferico = Periferico::find($id);
    
        if(!$periferico){
            $data = [
                'message' => 'Periférico no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }
    
        $validator = Validator::make($request->all(),[
            "Nombre" => "required|max:50|string",
            "Descripcion" => "required|string",
            "Precio" => "required|integer"
        ]);
    
        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }
    
        if ($periferico->Nombre == $request->Nombre && 
            $periferico->Descripcion == $request->Descripcion && 
            $periferico->Precio == $request->Precio) {
            
            return response()->json([
                'message' => 'No se necesita actualización, los datos ya están al día',
                'periferico' => $periferico,
                'status' => 200
            ]);
        }
    
        $periferico->Nombre = $request->Nombre;
        $periferico->Descripcion = $request->Descripcion;
        $periferico->Precio = $request->Precio;
    
        $periferico->save();
    
        $data = [
            'message' => 'Periférico actualizado',
            'periferico' => $periferico,
            'status' => 200
        ];
    
        return response()->json($data,200);
    }
    

    public function updatePatch(Request $request, $id){
        $periferico = Periferico::find($id);
    
        if(!$periferico){
            $data = [
                'message' => 'Periferico no encontrado',
                'status' => 404
            ];
            return response()->json($data,404);
        }
    
        $validator = Validator::make($request->all(),[
            "Nombre" => "|max:50|string|unique:perifericos,Nombre,".$periferico->id,  // Excluir el periférico actual de la validación
            "Descripcion" => "|string",
            "Precio" => "|integer"
        ]);
    
        if($validator->fails()){
            $data = [
                'message' => 'Error en la validacion de los datos',
                'errors' => $validator->errors(),
                'status' => 400
            ];
            return response()->json($data,400);
        }
    
        $esIgual = true;
        if ($request->has('Nombre') && $periferico->Nombre != $request->Nombre) {
            $periferico->Nombre = $request->Nombre;
            $esIgual = false;
        }
        if ($request->has('Descripcion') && $periferico->Descripcion != $request->Descripcion) {
            $periferico->Descripcion = $request->Descripcion;
            $esIgual = false;
        }
        if ($request->has('Precio') && $periferico->Precio != $request->Precio) {
            $periferico->Precio = $request->Precio;
            $esIgual = false;
        }
    
        if ($esIgual) {
            return response()->json([
                'message' => 'No se necesita actualización, los datos ya están al día',
                'periferico' => $periferico,
                'status' => 200
            ]);
        }
    
        $periferico->save();
    
        $data = [
            'message' => 'Periferico actualizado',
            'periferico' => $periferico,
            'status' => 200
        ];
    
        return response()->json($data,200);
    }
    
    
    
    

}
