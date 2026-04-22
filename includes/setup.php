<?php
require_once 'database.php';

try {
    $sentencia = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
    $sentencia->execute(['super']);

    if (!$sentencia->fetch()) {
        $contraseñaHash = password_hash('super', PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (nombre, contraseña, sucursal_id, rol, token_activacion) VALUES (?, ?, ?, ?, ?)";
        $sentencia = $pdo->prepare($sql);
        $sentencia->execute(['super', $contraseñaHash, null, 'super', null]);
    }
} catch (PDOException $e) {
    die('Error al configurar usuario super');
}
?>