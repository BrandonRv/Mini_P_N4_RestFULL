<?php

use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\DocenteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

///////////////////////////////////////////////////////////////////////////////////////
//--------------------------------- Rutas Alumnos -----------------------------------//
///////////////////////////////////////////////////////////////////////////////////////

Route::get('/alumnos', [AlumnoController::class, 'index']);
Route::get('/alumnos/{id}', [AlumnoController::class, 'show']);
Route::post('/alumnos', [AlumnoController::class, 'store']);
Route::put('/alumnos/{id}', [AlumnoController::class, 'update']);
Route::delete('/alumnos/{id}', [AlumnoController::class, 'destroy']);

//---------------------------------- Excepciones ------------------------------------//

Route::match(['put', 'delete'], '/alumnos', [AlumnoController::class, 'noIdExp']);
Route::match(['options', 'patch'], '/alumnos', [AlumnoController::class, 'wrongMethod']);
Route::match(['post', 'patch', 'options'], '/alumnos/{id}', [AlumnoController::class, 'wrongMethodId']);

///////////////////////////////////////////////////////////////////////////////////////
//--------------------------------- Rutas Docentes ----------------------------------//
///////////////////////////////////////////////////////////////////////////////////////

Route::get('/docentes', [DocenteController::class, 'index']);
Route::get('/docentes/{id}', [DocenteController::class, 'show']);
Route::post('/docentes', [DocenteController::class, 'store']);
Route::put('/docentes/{id}', [DocenteController::class, 'update']);
Route::delete('/docentes/{id}', [DocenteController::class, 'destroy']);

//---------------------------------- Excepciones ------------------------------------//

Route::match(['put', 'delete'], '/docentes', [DocenteController::class, 'noIdExp']);
Route::match(['options', 'patch'], '/docentes', [DocenteController::class, 'wrongMethod']);
Route::match(['post', 'patch', 'options'], '/docentes/{id}', [DocenteController::class, 'wrongMethodId']);

///////////////////////////////////////////////////////////////////////////////////////
//---------------------------------- Rutas Cursos -----------------------------------//
///////////////////////////////////////////////////////////////////////////////////////

Route::get('/cursos', [CursoController::class, 'index']);
Route::get('/cursos/{id}', [CursoController::class, 'show']);
Route::post('/cursos/{id}', [CursoController::class, 'store']);
Route::put('/cursos/{id}', [CursoController::class, 'update']);
Route::delete('/cursos/{id}', [CursoController::class, 'destroy']);

//---------------------------------- Excepciones ------------------------------------//

Route::match(['post', 'put', 'delete'], '/cursos', [CursoController::class, 'noIdExp']);
Route::match(['options', 'patch'], '/cursos', [CursoController::class, 'wrongMethod']);
Route::match(['patch', 'options'], '/cursos/{id}', [CursoController::class, 'wrongMethodId']);

///////////////////////////////////////////////////////////////////////////////////////
//-------------------------------- Rutas Matriculas ---------------------------------//
///////////////////////////////////////////////////////////////////////////////////////

Route::get('/matriculas', [MatriculaController::class, 'index']);
Route::get('/matriculas/{id}', [MatriculaController::class, 'show']);
Route::post('/matriculas', [MatriculaController::class, 'store']);
Route::put('/matriculas/{id}', [MatriculaController::class, 'update']);
Route::delete('/matriculas/{id}', [MatriculaController::class, 'destroy']);

//---------------------------------- Excepciones ------------------------------------//

Route::match(['put', 'delete'], '/matriculas', [MatriculaController::class, 'noIdExp']);
Route::match(['options', 'patch'], '/matriculas', [MatriculaController::class, 'wrongMethod']);
Route::match(['post', 'patch', 'options'], '/matriculas/{id}', [MatriculaController::class, 'wrongMethodId']);

