<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/auth.php';
require_once '../../vendor/phpmailer/PHPMailer.php';
require_once '../../vendor/phpmailer/Exception.php';
require_once '../../vendor/phpmailer/SMTP.php';

validar_rol(['super'], $pdo);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = strtolower($_POST['usuario']);
    $sucursal_id = $_POST['sucursal'];
    $especialidad = $_POST['especialidad'];
    $rol = 'manager';

    try {
        $pdo->beginTransaction();

        $token_activacion = bin2hex(random_bytes(8));
        $insertar = $pdo->prepare("INSERT INTO usuarios (nombre, sucursal_id, rol, especialidad, token_activacion) VALUES (?, ?, ?, ?, ?)");
        $insertar->execute([$usuario, $sucursal_id, $rol, $especialidad, $token_activacion]);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'localhost';
        $mail->Port = 1025;
        $mail->SMTPAuth = false;
        $mail->setFrom('sistema@tuproyecto.local', 'Sistema');
        $mail->addAddress('manager@tuproyecto.local');
        $mail->isHTML(true);
        $mail->Subject = 'Token de Activación';
        $mail->Body = "<a href='http://localhost/proyecto/pages/auth/activation.php?token=$token_activacion'>Activación de la cuenta</a>";
        $mail->send();

        $pdo->commit();
        $mensaje = 'Manager creado e email enviado.';
    } catch (PDOException $e) {
        $pdo->rollBack();

        if ($e->getCode() == 23000) {
            $mensaje = "El manager ya existe. Elige otro nombre.";
        } else {
            $mensaje = "No se pudo completar el proceso.";
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $mensaje = "No se pudo completar el proceso.";
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Affluens</title>
    <link rel="stylesheet" href="../../assets/css/reset.css" />
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <script src="../../assets/js/libs/jquery.js"></script>
    <script src="../../assets/js/dashboard/super.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>
    <header>
        <h1>Affluens</h1>
        <a href="estadisticas.php">Estadísticas →</a>
    </header>
    <main>
        <h2>Añadir manager</h2>
        <form method="POST">
            <input type="text" name="usuario" placeholder="Manager">
            <select name="especialidad">
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
            <button id="crearCuenta" type="button">CREAR CUENTA</button>
        </form>
    </main>
</body>

</html>