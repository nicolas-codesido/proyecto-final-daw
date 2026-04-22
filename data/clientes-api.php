<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/auth.php';

validar_rol(['asesor', 'manager'], $pdo);

$sentencia = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
$sentencia->execute([$_SESSION['usuario']]);
$usuario_actual = $sentencia->fetch(PDO::FETCH_ASSOC);

try {
    $consulta = "SELECT id, nombre FROM clientes WHERE usuario_id = ? ORDER BY nombre";
    $sentencia = $pdo->prepare($consulta);
    $sentencia->execute([$usuario_actual['id']]);
    $clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($clientes);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>