<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/auth.php';

$rol_usuario = validar_rol(['asesor', 'manager'], $pdo);
$es_manager = ($rol_usuario === 'manager');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affluens - Calendario</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/citas.css">
    <script src="../../assets/js/libs/jquery.js"></script>
    <script src='../../assets/js/libs/fullcalendar.min.js'></script>
    <script src="../../assets/js/modules/citas.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>

    <header>
        <a href="asesor.php" id="backLink">← Volver a clientes</a>
        <h1 id="mesActual"></h1>


    </header>

    <main>
        <div id="calendar"></div>
    </main>

    <div id="modalCita" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h3>Nueva Cita</h3>
            <p class="horario-info"><span id="horarioSeleccionado"></span></p>
            <form id="formCita">
                <div class="cliente-search-container">
                    <input type="text" id="clienteBuscar" placeholder="Escribe para buscar cliente..."
                        autocomplete="off">
                    <div id="resultadosClientes" class="cliente-resultados"></div>
                    <input type="hidden" id="clienteSeleccionado" name="cliente_id">
                </div>
                <input type="text" id="descripcion" placeholder="Descripción (opcional)" rows="3"></textarea>
                <div class="modal-buttons">
                    <button type="button" id="cancelarCita">Cancelar</button>
                    <button type="submit">Guardar Cita</button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>