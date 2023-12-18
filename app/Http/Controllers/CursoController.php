<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Curso;
use App\Models\Docente;
use App\Models\Matricula;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Curso::all();
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
    public function store(Request $request, $id)
    {
        if ($request->nombre_curso) {
            $curso = new Curso();
            if ($request->condicion == 1 || $request->condicion == 0) {
                $curso->nombre_curso = $request->nombre_curso;
                $curso->docente_id = $id;
                $curso->condicion = $request->condicion;
                $curso->save();
                return "El Curso se guardo correctamente";
            } else {
                return "'condicion' solo acepta los valores 0 ó 1.\n No se ha Creado el registro Aún.\n";
            }
        }
        return "Es nesesario ingresar un valor en el objeto de nombre_curso, para poder registrar un curso.";
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Curso::find($id) == null) {
            return "No existe un Curso con el id N° " . $id;
        }
        if (Curso::find($id)->condicion == 0) {
            return "El Curso N° " . $id . " esta desactivado.";
        }
        return Curso::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curso $curso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $condicionCurso = $request->condicion;
            if (!Curso::find($id)) {
                return "No existe un registro con el id : " . $id . ".";
            } else if ($request->nombre_curso) {
                $curso = Curso::find($id);
                if ($request->docente_id) {
                    $DocentesAll = Docente::where('condicion', '=', 1)->where('id', $request->docente_id)->get();
                    if (count($DocentesAll) == 0) {
                        return "No existe un docente con ese 'docente_id' o esta desactivado, porfavor ingrese un 'docente_id' valido.";
                    } else {
                        $this->updateALL($curso, $request, $condicionCurso, $id);
                        $curso->save();
                    }
                }
                if ($condicionCurso == 1 || $condicionCurso == 0) {
                    return "El registro con id : " . $id . " se actualizo Correctamente.";
                } else {
                    return "'condicion' solo acepta los valores '1' ó '0'.\n No se ha modificado 'condicion' Aún.\n";
                }
            } else {
                return "No se Actualizó nada. \n Es nesesario ingresar un valor al objeto: 'nombre_curso'\n para ser actualizado, en formato JSON";
            }
        } catch (Exception $e) {
            return "El valor nulo en el objeto condicion es invalido en el formato JSON";
        }
    }

    private function updateALL(Curso $curso, Request $request, $condicionCurso)
    {
        $curso->nombre_curso = $request->nombre_curso;
        $curso->docente_id =  $request->docente_id;
        if ($condicionCurso == 1 || $condicionCurso == 0) {
            $curso->condicion = $condicionCurso;
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $borrarCurso_tab = Curso::find($id);
        $borrar_curso = Matricula::where('cursos_id', '=', $id)->get();
        $id_curso =  $borrar_curso->pluck('id');
        if ($borrarCurso_tab == null) {
            return "No existe el Curso N° " . $id . ".";
        }
        foreach ($id_curso as $id_curso_individual) {
            Matricula::find($id_curso_individual)->delete();
        }
        $borrarCurso_tab->delete();
        return "El Curso N° " . $id . " ha sido eliminado Correctamente.";
    }

    public function noIdExp()
    {
        return "Es nesesario especificar un Id del docente en la Ruta.";
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
