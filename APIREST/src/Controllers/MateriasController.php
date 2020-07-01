<?php
namespace App\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface  as Request;
use App\Models\Materias;
use App\Models\Tipo;
use App\Models\User;
use App\Clases\Auth;
use App\Models\Materia;
use App\Models\Inscripto;

class MateriasController{

    public function add(Request $request, Response $response){
        $body = $request->getParsedBody();
        $id = Auth::validarToken($request->getHeader('token')[0])->id;
        $user = User::where('id', $id)->first();
        if($user->tipo->tipo == "admin"){
            $materia = new Materia;
            $materia->materia = $body['materia'];
            $materia->cuatrimestre = $body['cuatrimestre'];
            $materia->vacantes = $body['vacantes'];
            $materia->profesor_id = $body['profesor'];

            $response->getBody()->write(json_encode(array("ok" => $materia->save())));
        }
        else{
            $response->getBody()->write('Solo se admite el usuario admin.');
        }
        
        return $response;
    }

    public function getXId(Request $request, Response $response, array $args){
        $id = Auth::validarToken($request->getHeader('token')[0])->id;
        $user = User::where('id', $id)->first();
        $materia = Materia::where('id', $args['id'])->first();
        if($user->tipo->tipo == "alumno"){
            $obj = [
                "Materia" => $materia->materia,
                "Profesor" => $materia->profesor->nombre,
                "Vacantes" => $materia->vacantes,
                "Cuatrimestre" => $materia->cuatrimestre
            ];
            $response->getBody()->write(json_encode($obj));
        }
        else{
            $auxAlumnos = array();
            foreach ($materia->alumnos as $element) {
                array_push($auxAlumnos, [
                    "Nombre" => $element->alumno->nombre,
                    "Email" => $element->alumno->email,
                    "legajo" => $element->alumno->legajo
                ]);
            }
            $obj = [
                "materia" => $materia->materia,
                "profesor" => $materia->profesor->nombre,
                "vacantes" => $materia->vacantes,
                "cuatrimestre" => $materia->cuatrimestre,
                "alumnos" => $auxAlumnos
            ];
            $response->getBody()->write(json_encode($obj));
        }

        return $response;
    }

    public function setProfesor(Request $request, Response $response, array $args){
        $id = Auth::validarToken($request->getHeader('token')[0])->id;
        $user = User::where('id', $id)->first();
        $materia = Materia::where('id', $args['id'])->firstOrFail();
        if($user->tipo->tipo == "admin"){
            $materia->profesor_id = $args['profesor'];
            $response->getBody()->write(json_encode($materia->save()));
        }
        else{
            $response->getBody()->write(json_encode('TIPO INVÁLIDO'));
        }

        return $response;
    }

    public function inscribirse(Request $request, Response $response, array $args){
        $id = Auth::validarToken($request->getHeader('token')[0])->id;
        $user = User::where('id', $id)->first();
        $materia = Materia::where('id', $args['id'])->firstOrFail();

        if($user->tipo->tipo == "alumno"){
            if($materia->vacantes > 0){
                $inscribir = new Inscripto;
                $inscribir->materia_id = $materia->id;
                $inscribir->alumno_id = $user->id;
                $materia->vacantes -= 1;

                if($materia->save() && $inscribir->save()){
                    $response->getBody()->write(json_encode(array("ok" => 'Inscripción exitosa')));
                }
            }
            else{
                $response->getBody()->write(json_encode('NO HAY CUPO'));
            }
        }
        else{
            $response->getBody()->write(json_encode('TIPO INVÁLIDO'));
        }

        return $response;
    }

    public function get(Request $request, Response $response, array $args){
        $materia = Materia::All();
        $rtn = array();
        foreach ($materia as $element) {
            $cantidadInscriptos = count(Inscripto::where('materia_id', $element->id)->get());
            $obj = [
                "materia" => $element->materia,
                "vacantes" => $element->vacantes,
                "cuatrimestre" => $element->cuatrimestre,
                "profesor" => $element->profesor->nombre,
                "email" => $element->profesor->email,
                "inscriptos" => $cantidadInscriptos
            ];
            array_push($rtn, $obj);
        }
        $response->getBody()->write(json_encode($rtn));

        return $response;
    }
}