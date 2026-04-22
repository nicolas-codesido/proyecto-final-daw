<?php
session_start();
require_once '../../includes/database.php';
require_once '../../includes/auth.php';

$datos_graficos = array();

try {
    $pdo->beginTransaction();

    $consulta_patrimonio = "
        SELECT 
            s.nombre as sucursal,
            COALESCE(AVG(c.patrimonio), 0) as patrimonio_medio,
            COUNT(c.id) as total_clientes
        FROM sucursales s 
        LEFT JOIN usuarios u ON s.id = u.sucursal_id AND u.rol IN ('asesor', 'manager')
        LEFT JOIN clientes c ON u.id = c.usuario_id 
        GROUP BY s.id, s.nombre
        ORDER BY patrimonio_medio DESC
    ";

    $stmt_patrimonio = $pdo->prepare($consulta_patrimonio);
    $stmt_patrimonio->execute();
    $resultados_patrimonio = $stmt_patrimonio->fetchAll(PDO::FETCH_ASSOC);

    $patrimonio_labels = array();
    $patrimonio_data = array();
    $colores_patrimonio = array('#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF');

    foreach ($resultados_patrimonio as $fila) {
        $patrimonio_labels[] = $fila['sucursal'];
        $patrimonio_data[] = round($fila['patrimonio_medio'], 2);
    }

    $datos_graficos['patrimonio'] = array(
        'labels' => $patrimonio_labels,
        'datasets' => array(
            array(
                'label' => 'Patrimonio Medio (€)',
                'data' => $patrimonio_data,
                'backgroundColor' => array_slice($colores_patrimonio, 0, count($patrimonio_labels)),
                'borderColor' => array_slice($colores_patrimonio, 0, count($patrimonio_labels)),
                'borderWidth' => 1,
                'borderRadius' => 4,
                'borderSkipped' => false
            )
        )
    );

    $consulta_riesgo = "
        SELECT 
            perfil_riesgo,
            COUNT(*) as total
        FROM clientes 
        GROUP BY perfil_riesgo
        ORDER BY total DESC
    ";

    $stmt_riesgo = $pdo->prepare($consulta_riesgo);
    $stmt_riesgo->execute();
    $resultados_riesgo = $stmt_riesgo->fetchAll(PDO::FETCH_ASSOC);

    $riesgo_labels = array();
    $riesgo_data = array();
    $colores_riesgo = array(
        'Conservador' => '#4CAF50',
        'Moderado' => '#FF9800',
        'Agresivo' => '#F44336'
    );
    $riesgo_background = array();

    foreach ($resultados_riesgo as $fila) {
        $riesgo_labels[] = $fila['perfil_riesgo'];
        $riesgo_data[] = intval($fila['total']);
        $riesgo_background[] = $colores_riesgo[$fila['perfil_riesgo']];
    }

    $datos_graficos['riesgo'] = array(
        'labels' => $riesgo_labels,
        'datasets' => array(
            array(
                'data' => $riesgo_data,
                'backgroundColor' => $riesgo_background,
                'borderColor' => '#ffffff',
                'borderWidth' => 3,
                'hoverBorderWidth' => 5
            )
        )
    );

    $consulta_burbujas = "
        SELECT 
            s.nombre as sucursal,
            COUNT(DISTINCT c.id) as total_clientes,
            COALESCE(SUM(c.patrimonio), 0) as patrimonio_total,
            COUNT(DISTINCT u.id) as total_empleados
        FROM sucursales s 
        LEFT JOIN usuarios u ON s.id = u.sucursal_id AND u.rol IN ('asesor', 'manager')
        LEFT JOIN clientes c ON u.id = c.usuario_id 
        GROUP BY s.id, s.nombre
        ORDER BY s.nombre
    ";

    $stmt_burbujas = $pdo->prepare($consulta_burbujas);
    $stmt_burbujas->execute();
    $resultados_burbujas = $stmt_burbujas->fetchAll(PDO::FETCH_ASSOC);

    $burbujas_data = array();
    $colores_burbujas = array(
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)',
        'rgba(153, 102, 255, 0.7)'
    );

    $bordes_burbujas = array(
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)'
    );

    foreach ($resultados_burbujas as $indice => $fila) {
        $nombreCorto = str_replace(array(' Gran Vía', ' Gràcia', ' Centro', ' Catedral'), '', $fila['sucursal']);

        $burbujas_data[] = array(
            'label' => $nombreCorto,
            'data' => array(
                array(
                    'x' => intval($fila['total_clientes']),
                    'y' => round($fila['patrimonio_total'] / 1000, 0),
                    'r' => max(5, intval($fila['total_empleados']) * 4)
                )
            ),
            'backgroundColor' => $colores_burbujas[$indice % count($colores_burbujas)],
            'borderColor' => $bordes_burbujas[$indice % count($bordes_burbujas)],
            'borderWidth' => 2,
            'hoverBackgroundColor' => str_replace('0.7', '0.9', $colores_burbujas[$indice % count($colores_burbujas)]),
            'hoverBorderWidth' => 3
        );
    }

    $datos_graficos['burbujas'] = array(
        'datasets' => $burbujas_data
    );

    $consulta_top = "
        SELECT 
            c.nombre,
            c.patrimonio,
            s.nombre as sucursal
        FROM clientes c
        JOIN usuarios u ON c.usuario_id = u.id
        JOIN sucursales s ON u.sucursal_id = s.id
        ORDER BY c.patrimonio DESC
        LIMIT 10
    ";

    $stmt_top = $pdo->prepare($consulta_top);
    $stmt_top->execute();
    $resultados_top = $stmt_top->fetchAll(PDO::FETCH_ASSOC);

    $top_labels = array();
    $top_data = array();

    foreach ($resultados_top as $fila) {
        $nombrePartes = explode(' ', $fila['nombre']);
        $nombreCorto = $nombrePartes[0];

        $top_labels[] = $nombreCorto;
        $top_data[] = floatval($fila['patrimonio']);
    }

    $datos_graficos['topClientes'] = array(
        'labels' => $top_labels,
        'datasets' => array(
            array(
                'label' => 'Patrimonio (€)',
                'data' => $top_data,
                'backgroundColor' => '#FF6384',
                'borderColor' => '#FF6384',
                'borderWidth' => 1,
                'borderRadius' => 4,
                'borderSkipped' => false
            )
        )
    );

    $pdo->commit();

} catch (PDOException $e) {
    $pdo->rollBack();
    $error_db = "Error en la base de datos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Affluens</title>
    <link rel="stylesheet" href="../../assets/css/reset.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/estadisticas.css">
    <script src="../../assets/js/libs/chart.umd.js"></script>
    <script>
        <?php if (isset($datos_graficos)): ?>
            window.datosGraficos = <?php echo json_encode($datos_graficos); ?>;
        <?php else: ?>
            window.datosGraficos = null;
            window.errorDB = "<?php echo isset($error_db) ? $error_db : 'Error cargando datos'; ?>";
        <?php endif; ?>
    </script>
    <script src="../../assets/js/dashboard/estadisticas.js"></script>
</head>

<body>
    <a href="../auth/logout.php" class="logout-corner">↗</a>
    <header>
        <h1> Estadísticas</h1>
        <a href="super.php">← Volver a Panel Super</a>
    </header>

    <div class="dashboard-container">
        <div class="tabs-container">
            <div class="tabs-nav">
                <button class="tab-button active" data-tab="patrimonio">Patrimonio por Sucursal</button>
                <button class="tab-button" data-tab="riesgo">Perfil de Riesgo</button>
                <button class="tab-button" data-tab="clientes">Análisis por Sucursal</button>
                <button class="tab-button" data-tab="top-clientes">Top Clientes</button>
            </div>

            <div class="tab-content">
                <div class="chart-wrapper active" id="tab-patrimonio">
                    <div class="chart-title">Patrimonio Medio por Sucursal</div>
                    <div class="chart-container">
                        <canvas id="patrimonioChart"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper" id="tab-riesgo">
                    <div class="chart-title">Distribución por Perfil de Riesgo</div>
                    <div class="chart-container">
                        <canvas id="riesgoChart"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper" id="tab-clientes">
                    <div class="chart-title">Análisis Multidimensional por Sucursal</div>
                    <div class="chart-container">
                        <canvas id="clientesChart"></canvas>
                    </div>
                </div>

                <div class="chart-wrapper" id="tab-top-clientes">
                    <div class="chart-title">Top 10 Clientes por Patrimonio</div>
                    <div class="chart-container">
                        <canvas id="topClientesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>