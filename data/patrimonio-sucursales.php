<?php
session_start();
require_once '../includes/database.php';
require_once '../includes/auth.php';

validar_rol(array('super'), $pdo);

header('Content-Type: application/json');

try {
    $consulta = "
        SELECT 
            s.nombre as sucursal,
            COALESCE(AVG(c.patrimonio), 0) as patrimonio_medio
        FROM sucursales s 
        LEFT JOIN usuarios u ON s.id = u.sucursal_id 
        LEFT JOIN clientes c ON u.id = c.usuario_id 
        GROUP BY s.id, s.nombre
        ORDER BY patrimonio_medio DESC
    ";

    $sentencia = $pdo->prepare($consulta);
    $sentencia->execute();
    $resultados = $sentencia->fetchAll(PDO::FETCH_ASSOC);

    $labels = array();
    $data = array();

    foreach ($resultados as $fila) {
        $labels[] = $fila['sucursal'];
        $data[] = round($fila['patrimonio_medio'], 2);
    }

    $response = array(
        'labels' => $labels,
        'datasets' => array(
            array(
                'label' => 'Patrimonio Medio (€)',
                'data' => $data,
                'backgroundColor' => array(
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ),
                'borderColor' => array(
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF'
                ),
                'borderWidth' => 1
            )
        )
    );

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(array('error' => 'Error: ' . $e->getMessage()));
}
?>