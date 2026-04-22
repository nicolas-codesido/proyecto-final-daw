<?php

require_once '../includes/database.php';

$consulta = "SELECT * FROM sucursales";

try {
    $sentencia = $pdo->query($consulta);
    $salida = $sentencia->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $e) {
    $salida = ['error' => $e->getMessage()];
}

echo json_encode($salida);

?>