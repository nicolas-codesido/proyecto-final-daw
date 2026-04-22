<?php
session_start();
require_once 'includes/database.php';
require_once 'includes/setup.php';

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    try {
        $sentencia = $pdo->prepare("SELECT * FROM usuarios WHERE nombre = ?");
        $sentencia->execute([$usuario]);
        $usuarios = $sentencia->fetch(PDO::FETCH_ASSOC);

        if ($usuarios && password_verify($contraseña, $usuarios['contraseña'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $usuarios['rol'];
            $pagina_destino = $usuarios['rol'];
            if ($usuarios['rol'] === 'manager') {
                $pagina_destino = 'asesor';
            }

            header("Location: pages/dashboard/" . $pagina_destino . ".php");
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } catch (Exception $e) {
        $error = "Error en la base de datos.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Affluens - index</title>
    <link rel="stylesheet" href="assets/css/reset.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/all.min.css">
    <script src="assets/js/libs/jquery.js"></script>
    <script src="assets/js/auth/login.js"></script>
    <script src="assets/js/auth/password-toggle.js"></script>
</head>

<body>
    <header>
        <h1>&nbsp;</h1>
    </header>
    <main>
        <form method="POST">
            <img src="logo.jpg" alt="Affluens Logo" class="logo-form">
            <h2>Inicio de sesión</h2>
            <input type="text" name="usuario" placeholder="Usuario">
            <div class="inputContenedor">
                <input id="contraseñaInput" type="password" name="contraseña" placeholder="Contraseña">
                <i id="ojo1" class="fa-regular fa-eye visible ojo"></i>
            </div>
            <i class="obligatorio"><?php echo htmlspecialchars($error); ?></i>
            <button id="iniciarSesion" type="button">INICIAR SESIÓN</button>
        </form>
    </main>
</body>

</html>