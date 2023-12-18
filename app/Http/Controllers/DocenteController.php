<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Curso;
use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Docente::all();
        //return view('docentes.index', ['docentes' => $$docente]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('docentes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->nombre && $request->apellido) {
            $docente = new Docente();
            if ($request->condicion == 1 || $request->condicion == 0) {
                $docente->nombre = $request->nombre;
                $docente->apellido = $request->apellido;
                $docente->condicion = $request->condicion;
                $docente->save();
                return "El Profesor se guardo correctamente";
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
        if (Docente::find($id) == null) {
            return "No existe un docente con el id N° " . $id;
        }
        if (Docente::find($id)->condicion == 0) {
            return "El docente N° " . $id . " esta desactivado.";
        }
        return Docente::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Docente $docente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $condicionDoc = $request->condicion;
        if (!Docente::find($id)) {
            return "No existe un registro con el id : " . $id . ".";
        }
        if ($request->nombre && $request->apellido) {
            $docente = Docente::find($id);
            $this->updateALL($docente, $request, $condicionDoc);
            $docente->save();
            if ($condicionDoc == 1 || $condicionDoc == 0) {
                return "El registro con id : " . $id . " se actualizo Correctamente.";
            } else {
                return "'condicion' solo acepta los valores '1' ó '0'.\n No se ha modificado 'condicion' Aún.\n";
            }
        } else {
            return "No se Actualizó nada. \n Es nesesario ingresar un valor al objeto: 'nombre y apellido'\n para ser actualizado, en formato JSON";
        }
    }

    private function updateALL(Docente $docente, Request $request, $condicionDoc)
    {
        $docente->nombre = $request->nombre;
        $docente->apellido = $request->apellido;
        if ($condicionDoc == 1 || $condicionDoc == 0) {
            $docente->condicion = $condicionDoc;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $borrar_docente = Docente::find($id);
        $borrar_curso_doc = Curso::where('docente_id', '=', $id)->get();
        if ($borrar_docente == null) {
            return "No existe el docente N° " . $id . ".";
        }
        $borrar_curso_doc->each(function ($curso) {
            $curso->docente_id = null;
            $curso->save();
        });
        $borrar_docente->delete();
        return "El docente N° " . $id . " ha sido eliminado Correctamente.";
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
