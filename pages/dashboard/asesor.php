<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/auth.php';


$rol_usuario = validar_rol(['asesor', 'manager'], $pdo);
$es_manager = ($rol_usuario['rol'] === 'manager');
$sentencia = $pdo->prepare("SELECT id FROM usuarios WHERE nombre = ?");
$sentencia->execute([$_SESSION['usuario']]);
$usuario_actual = $sentencia->fetch(PDO::FETCH_ASSOC);

$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $dni = strtoupper($_POST['dni']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $patrimonio = $_POST['patrimonio'];
    $comision_porcentaje = $_POST['comision_porcentaje'];
    $perfil_riesgo = $_POST['perfil_riesgo'];
    $notas = $_POST['notas'];

    try {
        $insertar = $pdo->prepare("INSERT INTO clientes 
        (nombre, telefono, dni, fecha_nacimiento, patrimonio, comision_porcentaje, perfil_riesgo, usuario_id, notas)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insertar->execute([
            $nombre,
            $telefono,
            $dni,
            $fecha_nacimiento,
            $patrimonio,
            $comision_porcentaje,
            $perfil_riesgo,
            $usuario_actual['id'],
            $notas
        ]);

        $mensaje = 'Cliente agregado exitosamente.';
    } catch (PDOException $e) {
        $mensaje = "No se pudo agregar el cliente.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Affluens - Añadir Cliente</title>
    <link rel="stylesheet" href="../../assets/css/reset.css" />
    <link rel="stylesheet" href="../../assets/css/styles.css" />
    <script src="../../assets/js/libs/jquery.js"></script>
    <script src="../../assets/js/dashboard/asesor.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>
    <header>
        <h1>Clientes</h1>
        <?php if ($es_manager): ?>
            <a href="manager.php">← Gestionar asesores</a><br>
        <?php endif; ?>
        <a href="citas.php">Mis citas →</a>
    </header>
    <main>
        <form method="POST" class="form-grid">
            <div class="card">
                <h2>Datos personales</h2>
                <input type="text" name="nombre" placeholder="Nombre">
                <input type="text" name="dni" placeholder="DNI" maxlength="9">
                <input type="tel" name="telefono" placeholder="Teléfono" maxlength="9">
                <input type="date" name="fecha_nacimiento">
            </div>

            <div class="card">
                <h2>Datos financieros</h2>
                <input type="text" name="patrimonio" placeholder="Patrimonio">
                <input type="text" name="comision_porcentaje" placeholder="Comisión %">
                <select name="perfil_riesgo">
                    <option disabled selected>Perfil de riesgo</option>
                    <option value="Agresivo">Agresivo</option>
                    <option value="Conservador">Conservador</option>
                    <option value="Moderado">Moderado</option>
                </select>
                <input type="text" name="notas" placeholder="Notas">
            </div>

            <button type="button" id="añadirCliente">AÑADIR CLIENTE</button>
        </form>
    </main>

</body>

</html>