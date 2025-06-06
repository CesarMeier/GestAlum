<?php
require_once('../libr/Sanitize.class.php');
require_once('PersonaController.php');

$controller = new UserController();
$accion = $_GET['accion'] ?? '';

header('Content-Type: application/json');

switch ($accion) {
    case 'listar':
        echo json_encode($controller->getPersona());
        break;

    case 'guardar':
        // Validar los datos originales (no sanitizados)
        if (!$controller->validarDatos($_POST)) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Todos los campos deben ser válidos']);
            exit;
        }

        // Sanitizar los datos después de validar
        $sanitizedData = $controller->getSanitizedInput($_POST);

        // Guardar con los datos sanitizados
        echo json_encode(['success' => true, 'id' => $controller->save($sanitizedData)]);
        break;

    case 'eliminar':
        $id = $_POST['id'] ?? null;
        echo json_encode($controller->deletePersona($id));
        break;

    case 'duplicar':
        echo json_encode($controller->doublePersona($_POST));
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Acción no válida']);
        break;
}