<?php
set_include_path('../modelo/'.PATH_SEPARATOR.'../Libr/');
require_once('Sanitize.class.php');
require_once('SanitizeCustom.php');
require_once('Persona.php');

class UserController {

    public function validarDatos($post) { 
        $id = $post['idPersona'] ?? null;  // o 'id' según tu input en el formulario
        $dni = $post['dni'] ?? '';
        $nombre = $post['nombre'] ?? '';
        $apellido = $post['apellido'] ?? '';
        $telefono = $post['telefono'] ?? '';
        $email = $post['email'] ?? '';
        $cuil = $post['cuil'] ?? '';

        $result = [];

        // Validar que el id sea un número entero positivo, si existe (para edición)
        if ($id !== null) {
            $result['idPersona'] = filter_var($id, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]) !== false;
        } else {
            // Para creación nueva, id no es obligatorio
            $result['idPersona'] = true;
        }

        // Validar DNI: 7 u 8 dígitos numéricos
        $result['dni'] = preg_match('/^\d{7,8}$/', $dni) === 1;

        // Validar nombre: solo letras y espacios, mínimo 2 caracteres
        $result['nombre'] = preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/', $nombre) === 1;

        // Validar apellido: solo letras y espacios, mínimo 2 caracteres
        $result['apellido'] = preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,}$/', $apellido) === 1;

        // Validar teléfono: +, espacios, guiones y entre 6 y 15 dígitos en total
        $result['telefono'] = preg_match('/^\+?[\d\s\-]{6,15}$/', $telefono) === 1;

        // Validar email con filtro PHP
        $result['email'] = filter_var($email, FILTER_VALIDATE_EMAIL) !== false;

        // Validar CUIL: formato XX-XXXXXXXX-X
        $result['cuil'] = preg_match('/^\d{11}$/', $cuil) === 1;

        // Para retornar true si todo es válido:
        return !in_array(false, $result, true);

        // O si querés, podés devolver el array con detalle:
        // return $result;
    }


    //funcion de sanitizacion
    public function getSanitizedInput($post) {
        return [
            'dni' => isset($post['dni']) ? SanitizeCustom::DNI($post['dni']) : false,
            'nombre' => isset($post['nombre']) ? SanitizeVars::STRING($post['nombre'], 3, 60) : false,
            'apellido' => isset($post['apellido']) ? SanitizeVars::STRING($post['apellido'], 2, 60) : false,
            'email' => isset($post['email']) ? SanitizeVars::EMAIL($post['email']) : false,
            'telefono'=> isset($post['telefono']) ? SanitizeVars::STRING($post['telefono'],10,13) : false,
            'cuil' => isset($post['cuil']) ? SanitizeCustom::CUIL($post['cuil']) : false,
        ];
    }

    //funcion eliminar persona
    public function deletePersona($id) {
        if (!$id) {
            http_response_code(409);
            return ['success' => false, 'error' => 'ID no recibido'];
        }

        $objPersona = new Persona();
        $success = $objPersona->delete($id);
        return ['success' => $success];
    }



    //funcion duplicar persona 
    public function doublePersona($param) {
        $camposObligatorios = ['dni', 'nombre', 'apellido', 'email', 'telefono', 'cuil'];
        $camposFaltantes = [];

        foreach ($camposObligatorios as $campo) {
            if (empty($param[$campo])) {
                $camposFaltantes[] = $campo;
            }
        }

        if (!empty($camposFaltantes)) {
            http_response_code(409);
            return [
                'success' => false,
                'error' => 'Faltan los siguientes campos: ' . implode(', ', $camposFaltantes)
            ];
        }

        unset($param['id'], $param['idPersona']);

        $objPersona = new Persona();

        if ($objPersona->existePorDNI($param['dni'])) {
            http_response_code(409);
            return ['success' => false, 'error' => 'El DNI ya existe'];
        }

        if ($objPersona->existePorEmail($param['email'])) {
            http_response_code(409);
            return ['success' => false, 'error' => 'El email ya existe'];
        }

        if ($objPersona->existePorCUIL($param['cuil'])) {
            http_response_code(409);
            return ['success' => false, 'error' => 'El CUIL ya existe'];
        }

        $idNuevaPersona = $objPersona->save($param);

        return ['success' => true, 'idNuevaPersona' => $idNuevaPersona];
    }



    //funcion para obtener los datos de la tabla
    public function getPersona() {
        $objPersona = new Persona();
        return $objPersona->getPersona();
    }



    //funcion para guardar nuevos datos
    public function save($param) {
        $objPersona = new Persona();
        $idPersona = $objPersona->save($param);
        return $idPersona;
    }
}
?>