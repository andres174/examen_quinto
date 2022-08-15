<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Animal;
use App\Models\TipoAnimal;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use PDF;


class UserController extends Controller
{
    // USER

    public function register(Request $request)
    {
        $validData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|max:8',
            
        ]);
        User::create([
            'name' => $validData['name'],
            'email' => $validData['email'],
            'password' => Hash::make($validData['password']),
            
        ]);
        return response()->json(['message' => 'Usuario registrado'], 201);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only('name', 'password'))) {
            return response()->json(['message' => 'Credenciales invalidas'], 401);
        }

        $user = User::where('name', $request->name)->first();
        $token = $request->user()->createToken('auth_token')->plainTextToken;

        return response()->json(
            [
                'accesToken'=>$token,
                'tokenType'=>'Bearer'
            ],
            200

        );

    }

    public function showUsers()
    {
        $users = User::all();
        return response()->json($users, 200);
    }




    //ANIMAL

    public function showAnimal()
      {
        $animal = Animal::where('eliminado', 1)->get();
        return response()->json($animal, 200);
      }

    //Ingresar animal
    public function ingresarAnimal(Request $request)
    {
        $validData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_id' => 'required'
        ]);
        $a = Animal::create([
            'nombre' => $validData['nombre'],
            'tipo_id' => $validData['tipo_id'],
            'eliminado' => 1,
        ]);
        /* $this->logo->store('','fotos'); */

        $customFileName;
        
        if ($request->imagen) {
            $customFileName = uniqid() . '_.' . $request->imagen->extension();
            $request->imagen->storeAs('public/animal', $customFileName);
            $a->imagen = $customFileName;
            $a->save();
        }
        return response()->json(['message' => 'Animal registrado'], 201);


        
    }

    //Borrar animal
    public function destroyAnimal($id)
    {
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return response()->json(['message' => 'Animal no encontrado'], 404);
        }
        $animal->eliminado = 0;
        $animal->save();
        return response()->json(['message' => 'Animal eliminado'], 200);
    }


    //busca para editar
    public function editAnimal($id)
    {
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return response()->json(['message' => 'Animal no encontrado'], 404);
        }
        return response()->json($animal, 200);    
    } 

    //El que edita de verdad
    public function updateAnimal(Request $request, $id)
    {
        $animal = Animal::find($id);
        if (is_null($animal)) {
            return response()->json(['message' => 'Animal no encontrado'], 404);
        }
        $validateData = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo_id' => 'required'
        ]);
        $animal->nombre = $validateData['nombre'];
        $animal->tipo_id = $validateData['tipo_id'];

        $customFileName;
        
        if ($request->imagen) {
            $customFileName = uniqid() . '_.' . $request->imagen->extension();
            $request->imagen->storeAs('public/animal', $customFileName);
            $imageTemp = $animal->imagen; //imagen temporal
            $animal->imagen = $customFileName;
            
            $animal->save();

            if($imageTemp!=null)
            {    
                if(file_exists('public/animal' . $imageTemp));
                {
                    Storage::delete('public/animal' . $imageTemp);
                }
            }
            
            
        }
        
        return response()->json(['message' => 'Animal actualizado'], 201);


        
    }
    // mostrar tipos
    public function showTipos(){
        $tipo = TipoAnimal::where('eliminado', 1)->get();
        return response()->json($tipo, 200);
    }

    //buscar animal por tipo
    /* public function buscarTipoI($tipo){
        $animal = Animal::where()
    }
 */


    public function reporte(){
        /* $this->r(); */
        $datos = DB::table('animals')
        ->join('tipo_animals', 'animals.tipo_id', '=', 'tipo_animals.id')
        ->select('animals.*', 'tipo_animals.tipo_animal')
        ->where('animals.eliminado', 1)
        ->get();

        $r= PDF::loadView('reporte', compact('datos'));
        

        return response()->json($r->download('reporte.pdf'), 200);
    }

    /* public function r(){
        $datos = DB::table('animals')
        ->join('tipo_animals', 'animals.tipo_id', '=', 'tipo_animals.id')
        ->select('animals.*', 'tipo_animals.tipo_animal')
        ->where('animals.eliminado', 1)
        ->get();

        return PDF::loadView('reporte', compact('datos'))
        ->download('reporte.pdf');
    } */
}
