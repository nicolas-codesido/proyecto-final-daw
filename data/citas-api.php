<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/auth.php';

validar_rol(['asesor', 'manager'], $pdo);

$sentencia = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
$sentencia->execute([$_SESSION['usuario']]);
$usuario_actual = $sentencia->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input && $input['accion'] === 'crear') {
        try {
            $insertar = $pdo->prepare("INSERT INTO citas (descripcion, fecha_inicio, fecha_fin, cliente_id, usuario_id) VALUES (?, ?, ?, ?, ?)");
            $insertar->execute([
                $input['descripcion'] ?? '',
                $input['fechaInicio'],
                $input['fechaFin'],
                $input['clienteId'],
                $usuario_actual['id']
            ]);

            echo json_encode(['success' => true, 'message' => 'Cita creada correctamente']);
            exit;
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
            exit;
        }
    }
}

try {
    $vista = $_GET['vista'] ?? 'dayGridMonth';
    $eventos = [];

    if ($vista === 'timeGridDay') {
        $consulta_individual = "SELECT 
            c.id,
            c.descripcion,
            c.fecha_inicio,
            c.fecha_fin,
            cl.nombre as cliente_nombre
            FROM citas c 
            LEFT JOIN clientes cl ON c.cliente_id = cl.id
            WHERE c.usuario_id = ?
            ORDER BY c.fecha_inicio";

        $sentencia = $pdo->prepare($consulta_individual);
        $sentencia->execute([$usuario_actual['id']]);
        $citas_individuales = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        foreach ($citas_individuales as $cita) {
            $titulo = "Reunión con " . ($cita['cliente_nombre'] ?: 'Cliente no asignado');
            if (!empty($cita['descripcion'])) {
                $titulo .= ". " . $cita['descripcion'];
            }

            $eventos[] = [
                'id' => 'cita-' . $cita['id'],
                'title' => $titulo,
                'start' => $cita['fecha_inicio'],
                'end' => $cita['fecha_fin'],
                'extendedProps' => [
                    'esResumen' => false,
                    'descripcion' => $cita['descripcion'],
                    'clienteNombre' => $cita['cliente_nombre']
                ]
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($eventos);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>