<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Alumno;
use App\Models\Matricula;
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Alumno::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->nombre && $request->apellido) {
            $alumno = new Alumno();
            if ($request->condicion == 1 || $request->condicion == 0) {
                $alumno->nombre = $request->nombre;
                $alumno->apellido = $request->apellido;
                $alumno->condicion = $request->condicion;
                $alumno->save();
                return "El Alumno se guardo correctamente";
            } else {
                return "'condicion' solo acepta los valores 0 ó 1.\n No se ha Creado el registro Aún.\n";
            }
        }
        return "Es nesesario ingresar un valor en el objeto de nombre y apellido, para ser registrar un alumno.";
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Alumno::find($id) == null) {
            return "No existe el alumno con el id N° " . $id;
        }
        if (Alumno::find($id)->condicion == 0) {
            return "El alumno N° " . $id . " esta desactivado.";
        }
        return Alumno::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alumno $alumno)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $condicionAlu = $request->condicion;
        if (!Alumno::find($id)) {
            return "No existe un registro con el id : " . $id . ".";
        }
        if ($request->nombre && $request->apellido) {
            $alumno = Alumno::find($id);
            $this->updateALL($alumno, $request, $condicionAlu);
            $alumno->save();
            if ($condicionAlu == 1 || $condicionAlu == 0) {
                return "El registro con id : " . $id . " se actualizo Correctamente. " . $condicionAlu;
            } else {
                return "'condicion' solo acepta los valores '1' ó '0'.\n No se ha modificado 'condicion' Aún.\n";
            }
        } else {
            return "No se Actualizó nada. \n Es nesesario ingresar un valor al objeto: 'nombre y apellido'\n para ser actualizado, en formato JSON";
        }
    }

    private function updateALL(Alumno $alumno, Request $request, $condicionAlu)
    {
        $alumno->nombre = $request->nombre;
        $alumno->apellido = $request->apellido;
        if ($condicionAlu == 1 || $condicionAlu == 0) {
            $alumno->condicion = $condicionAlu;
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $borrar_alumno = Alumno::find($id);
        $borrar_matricula = Matricula::where('alumnos_id', '=', $id)->get();
        $id_matricula =  $borrar_matricula->pluck('id');
        if ($borrar_alumno == null) {
            return "No existe el alumno N° " . $id . ".";
        }
        foreach ($id_matricula as $id_matricula_individual) {
            Matricula::find($id_matricula_individual)->delete();
        }
        $borrar_alumno->delete();
        return "El alumno N° " . $id . " ha sido eliminado Correctamente.";
    }
    public function noIdExp()
    {
        return "Es nesesario especificar un Id en la Ruta.";
    }
    public function wrongMethod()
    {
        return "Metodo no aceptado. Metodos permitidos:  PUT, DELETE";
    }
    public function wrongMethodId()
    {
        return "Metodo no aceptado. Metodos permitidos: GET, PUT, DELETE";
    }
}
