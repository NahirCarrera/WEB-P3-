<?php
include 'config.php'; // Asegúrate de que este archivo contiene los detalles de conexión a tu base de datos
require_once "helpers.php"; // Si necesitas funciones de ayuda

// Verifica si ID_periodo está establecido y no es nulo
if(isset($_POST['ID_periodo'])) {
    $selectedPeriodId = $_POST['ID_periodo'];

    // Iniciar una transacción para garantizar la integridad de los datos
    $link->begin_transaction();

    // Primero, actualizar el estado de todos los períodos a 0
    $queryReset = "UPDATE periodos SET estado = 0";
    $stmtReset = $link->prepare($queryReset);
    $stmtReset->execute();
    $stmtReset->close();

    // Luego, actualizar el estado del período seleccionado a 1
    $queryUpdate = "UPDATE periodos SET estado = 1 WHERE ID_periodo = ?";
    $stmtUpdate = $link->prepare($queryUpdate);
    $stmtUpdate->bind_param("i", $selectedPeriodId);
    if($stmtUpdate->execute()) {
        // Si la actualización fue exitosa, confirmar la transacción
        $link->commit();
        echo json_encode(['success' => true, 'message' => 'Período actualizado correctamente.']);
    } else {
        // Si hubo un error, revertir la transacción
        $link->rollback();
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el período.']);
    }

    $stmtUpdate->close();
} else {
    echo json_encode(['success' => false, 'message' => 'ID de período no proporcionado.']);
}

$link->close();
?>
