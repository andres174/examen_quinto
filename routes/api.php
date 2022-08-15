<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {
    Route::get('v1/usuarios', [UserController::class, 'showUsers']);

    //ANIMAL

    Route::post('v1/ingresar-animal',[ UserController::class,'ingresarAnimal']);
    Route::post('v1/animal-update/{id}',[ UserController::class,'updateAnimal']);
    Route::get('v1/animal-edit/{id}',[ UserController::class,'editAnimal']);
    Route::post('v1/animal-destroy/{id}',[ UserController::class,'destroyAnimal']);
    Route::get('v1/animal-show',[ UserController::class,'showAnimal']);  // muestra todos los animales
    /* -------- */
    Route::get('v1/tipos-show',[ UserController::class,'showTipos']);  // muestra todos los tipos


    
    
    
});

Route::post('v1/register', [UserController::class, 'register']);

Route::post('v1/login', [UserController::class, 'login']);

Route::get('v1/reporte',[ UserController::class,'reporte']);
