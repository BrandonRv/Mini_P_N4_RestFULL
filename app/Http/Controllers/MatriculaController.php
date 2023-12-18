<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Curso;
use App\Models\Alumno;
use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Matricula::all();
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
        if ($request->cursos_id && $request->alumnos_id) {
            $nuevaMatricula = new Matricula();
            $nuevaMatricula->cursos_id = $request->cursos_id;
            $nuevaMatricula->alumnos_id = $request->alumnos_id;

            $CursosAll = Curso::where('condicion', '=', 1)->where('id', $request->cursos_id)->get();
            $AlumnosAll = Alumno::where('condicion', '=', 1)->where('id', $request->alumnos_id)->get();

            if (count($CursosAll) == 0) {
                return "No existe un curso con ese 'cursos_id' o esta desactivado, porfavor ingrese un 'cursos_id' valido.";
            } else {
                $nuevaMatricula->cursos_id = $request->cursos_id;
            }
            if (count($AlumnosAll) == 0) {
                return "No existe un alumno con ese 'alumnos_id' o esta desactivado, porfavor ingrese un 'alumnos_id' valido.";
            } else {
                $nuevaMatricula->alumnos_id = $request->alumnos_id;
            }
            $nuevaMatricula->asistencia = NULL;
            $nuevaMatricula->condicion = 1;
            $nuevaMatricula->save();
            return "Alumno N° " . $request->alumnos_id . " Ha sido matriculado \nCorrectamente en el curso " . $request->cursos_id . ".";
        }
        return "Es nesesario ingresar un valor en el objeto de nombre: 'cursos_id' y 'alumnos_id' para ser matricular a un alumno en un curso.";
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (Matricula::find($id) == null) {
            return "No existe la matricula con el id N° " . $id;
        }
        if (Matricula::find($id)->condicion == 0) {
            return "El matricula N° " . $id . " esta desactivada.";
        }
        return Matricula::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matricula $matricula)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            if (Matricula::find($id) != null) {
                $matricula = Matricula::find($id);
                if ($request->update == 1) { // PARA ACTUALIZAR TODO
                    $this->updateAll($matricula, $request, $id);
                } else if ($request->update == 2) { // PARA ACTUALIZAR ASISTENCIA
                    $this->asistenciaInsert($request, $id);
                } else if ($request->update == 3) { // PARA ELEMINAR ASISTENCIA
                    $this->asistenciaDelete($id);
                }
            } else {
                return "No existe un registro con el id : " . $id . ".";
            }
        } catch (Exception $e) {
            return "Es nesesario ingresar un valor en el objeto 'alumnos_id' y 'cursos_id' para ser actualizado, en formato JSON";
        }
    }

    private function updateAll(Matricula $matricula, Request $request, $id)
    {
        if ($request->asistencia) {
            $AlumnosAll = Alumno::where('state', '=', 1)->where('id', $request->alumnos_id)->get();

            if (count($AlumnosAll) == 0) {
                return "No existe un alumno con ese 'alumnos_id' o esta desactivado, porfavor ingrese un 'alumnos_id' valido.";
            } else {
                $matricula->alumnos_id = $request->alumnos_id;
            }
        }
        if ($request->cursos_id) {
            $CursosAll = Curso::where('state', '=', 1)->where('id', $request->cursos_id)->get();

            if (count($CursosAll) == 0) {
                return "No existe un curso con ese 'cursos_id' o esta desactivado, porfavor ingrese un 'cursos_id' valido.";
            } else {
                $matricula->cursos_id = $request->cursos_id;
            }
        }
        if ($request->state) {
            if ($request->state == 1 || $request->state == 0) {
                $matricula->state = $request->state;
            } else {
                return "'state' solo acepta los valores 0 o 1.\n 'state' sin modificaciones.\n";
            }
        }
        if ($request->asistencia) {
            if ($request->asistencia == "A" || $request->asistencia == "T" || $request->asistencia == "F") {
                $matricula->asistencia = $request->asistencia;
            } else {
                return "'asistencia' solo acepta los valores 'A', 'T' o 'F'.\n 'asistencia' sin modificaciones.\n";
            }
        }
    }
    private function asistenciaInsert(Request $request, $id)
    {
        $asistencia = Matricula::find($id);
        if ($request->asistencia) {
            if ($request->asistencia == "A" || $request->asistencia == "T" || $request->asistencia == "F") {
                $asistencia->asistencia = $request->asistencia;
                $asistencia->save();
                return "Asistencia registrada para el id " . $id;
            } else {
                return "'asistencia' solo acepta los valores 'A', 'T' o 'F'.\n 'asistencia' sin modificaciones.\n";
            }
        }
        return "Es nesesario ingresar un valor en el objeto de 'asistencia:' para ser tomar la asistencia.";
    }
    private function asistenciaDelete($id)
    {
        $borrarAsistencia = Matricula::find($id);
        if ($borrarAsistencia->asistencia == null) {
            return "No hay una asistencia registrada.";
        }
        if ($borrarAsistencia == null) {
            return "No existe alguien matriculado con el id N° " . $id . ", por eso no se puede borrar asistencia.";
        }
        $borrarAsistencia->asistencia = null;
        $borrarAsistencia->save();
        return "Se ha eliminado la asistencia.";
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $borrarMatricula = Matricula::find($id);
        if ($borrarMatricula == null) {
            return "No existe la matricula N° " . $id . " o fue Eliminada YA.";
        } else if ($borrarMatricula->condicion == 0) {
            return "La matricula N° " . $id . " esta desactivada.";
        }

        $borrarMatricula->delete();
        return "La Matricula N° " . $id . " ha sido eliminada.";
    }

    public function noIdExp()
    {
        return "Es nesesario especificar un Id en la route.";
    }
    public function wrongMethod()
    {
        return "Metodo no aceptado. Metodos permitidos:  PUT, DELETE";
    }
    public function wrongMethodId()
    {
        return "Metodo no aceptado. Metodos permitidos: GET, PUT, DELETE";
    }
    public function wrongMethodId2()
    {
        return "Metodo no aceptado. Metodos permitidos: POST, PUT, DELETE";
    }
}
