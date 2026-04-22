<?php
require_once '../../includes/database.php';

$mensaje = '';

$token = isset($_GET['token']) ? trim($_GET['token']) : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $contraseña = $_POST['contraseña'];

    try {
        $sentencia = $pdo->prepare("SELECT id FROM usuarios WHERE token_activacion = ?");
        $sentencia->execute([$token]);
        $usuario = $sentencia->fetch();

        if ($usuario) {
            $contraseñaHasheada = password_hash($contraseña, PASSWORD_DEFAULT);
            $actualizar = $pdo->prepare("UPDATE usuarios SET contraseña = ?, token_activacion = NULL WHERE id = ?");
            $actualizar->execute([$contraseñaHasheada, $usuario['id']]);
            $mensaje = 'Cuenta activada correctamente.';
        } else {
            $mensaje = "Token inválido o cuenta ya activada.";
        }
    } catch (PDOException $e) {
        $mensaje = "Error en la base de datos.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Affluens - sign up</title>
    <link rel="stylesheet" href="../../assets/css/reset.css" />
    <link rel="stylesheet" href="../../assets/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <script src="../../assets/js/libs/jquery.js"></script>
    <script src="../../assets/js/auth/activation.js"></script>
    <script src="../../assets/js/auth/password-toggle.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>
    <header>
        <h1>Affluens</h1>
    </header>
    <main>
        <h2>Activación cuenta</h2>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />
            <div class="inputContenedor">
                <input id="contraseñaToken" type="password" name="contraseña" placeholder="Contraseña" />
                <i id="ojo2" class="fa-regular fa-eye visible ojo"></i>
            </div>
            <div class="inputContenedor">
                <input id="confirmarToken" type="password" name="confirmarContraseña"
                    placeholder="Confirma tu contraseña" />
                <i id="ojo3" class="fa-regular fa-eye visible ojo"></i>
            </div>
            <i class="obligatorio">
                <?php if (!empty($mensaje)): ?>
                    <?php echo $mensaje; ?>
                <?php endif; ?></i>
            <button id="activarCuenta" type="button">ACTIVAR CUENTA</button>
        </form>
    </main>
</body>

</html>