<?php
function validar_rol($roles_permitidos, $pdo)
{
    if (!isset($_SESSION['usuario'])) {
        header("Location: ../../index.php");
        exit;
    }

    try {
        $sentencia = $pdo->prepare("SELECT rol, token_activacion FROM usuarios WHERE nombre = ?");
        $sentencia->execute([$_SESSION['usuario']]);
        $usuario = $sentencia->fetch(PDO::FETCH_ASSOC);

        if (!$usuario || $usuario['token_activacion'] !== null) {
            session_destroy();
            header("Location: ../../index.php");
            exit;
        }

        if (!in_array($usuario['rol'], $roles_permitidos)) {
            header("Location: " . $usuario['rol'] . ".php");
            exit;
        }

        return $usuario;

    } catch (PDOException $e) {
        header("Location: ../../index.php");
        exit;
    }
}
?>