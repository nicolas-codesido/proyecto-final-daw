<?php

require_once '../includes/database.php';

$consulta = "SELECT sucursal_id FROM usuarios WHERE rol = 'manager' AND sucursal_id IS NOT NULL";

try {
    $sentencia = $pdo->query($consulta);
    $salida = $sentencia->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $salida = ['error' => $e->getMessage()];
}

echo json_encode($salida);

?>