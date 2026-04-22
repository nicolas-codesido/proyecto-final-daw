<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/auth.php';
require_once '../../vendor/phpmailer/PHPMailer.php';
require_once '../../vendor/phpmailer/Exception.php';
require_once '../../vendor/phpmailer/SMTP.php';

validar_rol(['manager'], $pdo);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";

$sentencia = $pdo->prepare("SELECT sucursal_id FROM usuarios WHERE nombre = ?");
$sentencia->execute([$_SESSION['usuario']]);
$manager = $sentencia->fetch(PDO::FETCH_ASSOC);
$sucursal_manager = $manager['sucursal_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = strtolower($_POST['usuario']);
    $especialidad = $_POST['especialidad'];
    $rol = 'asesor';

    try {
        $pdo->beginTransaction();

        $token_activacion = bin2hex(random_bytes(8));


        $insertar = $pdo->prepare("INSERT INTO usuarios (nombre, sucursal_id, rol, especialidad, token_activacion) VALUES (?, ?, ?, ?, ?)");
        $insertar->execute([$usuario, $sucursal_manager, $rol, $especialidad, $token_activacion]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->setFrom('sistema@tuproyecto.local', 'Sistema');
        $mail->addAddress('usuario@tuproyecto.local');
        $mail->isHTML(true);
        $mail->Subject = 'Token de Activación';
        $mail->Body = "<a href='http://localhost/proyecto/pages/auth/activation.php?token=$token_activacion'>Activar cuenta</a>";
        $mail->send();

        $pdo->commit();
        $mensaje = 'Asesor creado correctamente.';
    } catch (PDOException $e) {
        $pdo->rollBack();

        if ($e->getCode() == 23000) {
            $mensaje = "El asesor ya existe.";
        } else {
            $mensaje = "No se pudo crear el asesor.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensaje = "No se pudo crear el usuario.";
    }

}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Affluens - manager</title>
    <link rel="stylesheet" href="../../assets/css/reset.css" />
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <script src="../../assets/js/libs/jquery.js"></script>
    <script src="../../assets/js/dashboard/manager.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>
    <header>
        <h1>Asesores</h1>
        <a href="asesor.php">Ir a clientes →</a>
    </header>
    <main>
        <h2>Crear asesor</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Nombre">
            <select name="especialidad" required>
                <option disabled selected>Especialidad</option>
                <option value="General">General</option>
                <option value="Fondos">Fondos</option>
                <option value="Créditos">Créditos</option>
                <option value="Inversiones">Inversiones</option>
                <option value="Patrimonial">Patrimonial</option>
            </select>
            <i class="obligatorio">
                <?php if (!empty($mensaje)): ?>
                    <?php echo $mensaje; ?>
                <?php endif; ?>
            </i>
            <button id="crearAsesor" type="button">CREAR</button>
        </form>
    </main>

</body>

</html>