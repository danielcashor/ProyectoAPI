<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\perifericoController;

Route::get('/perifericos',[perifericoController::class, 'index']);

Route::get('/perifericos/{id}',[perifericoController::class, 'show']);
Route::post('/perifericos',[perifericoController::class,'store']);

Route::put('/perifericos/{id}',[perifericoController::class, 'update']);

Route::patch('/perifericos/{id}',[perifericoController::class, 'updatePatch']);


Route::delete('/perifericos/{id}',[perifericoController::class, 'borrar']);

