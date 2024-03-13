<?php
// Incluir conexión a base de datos
include 'config.php';
require_once "helpers.php";

/*// Obtener datos del formulario
$codigoNRC = $_POST['nrc'];

// Primero, encontrar el ID de NRC que coincida con el código NRC proporcionado
$queryBuscarNRC = $link->prepare("SELECT ID_NRC FROM nrcs WHERE codigo = ?");
$queryBuscarNRC->bind_param("s", $codigoNRC);
$queryBuscarNRC->execute();
$resultadoNRC = $queryBuscarNRC->get_result();

if ($resultadoNRC->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => "No se encontró el NRC correspondiente."]);
    exit;
}

$filaNRC = $resultadoNRC->fetch_assoc();
$nrcID = $filaNRC['ID_NRC'];

// Eliminar reservas anteriores que coincidan con el NRC, aula y horario dados
$queryEliminarReservasEspecificas = $link->prepare("
    DELETE ra FROM reserva_aula ra
	JOIN aulas_horarios ah ON ra.AULAS_HORARIOS_ID_aulas_horarios = ah.ID_aulas_horarios
	WHERE ra.NRCS_ID_NRC = ?;

");

$queryEliminarReservasEspecificas->bind_param("i", $nrcID);

if ($queryEliminarReservasEspecificas->execute()) {
    echo json_encode(['success' => true, 'message' => 'Reservas específicas eliminadas con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar reservas específicas']);
}*/
// Primero, encontrar el ID de periodo activo 
$queryBuscarPeriodo = $link->prepare("SELECT ID_periodo FROM periodos WHERE estado = 1");

$queryBuscarPeriodo->execute();
$resultadoPeriodo = $queryBuscarPeriodo->get_result();

if ($resultadoPeriodo->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => "No se encontró el periodo activo."]);
    exit;
}

$filaPeriodo = $resultadoPeriodo->fetch_assoc();
$ID_periodo = $filaPeriodo['ID_periodo'];


// Eliminar reservas anteriores que coincidan con el NRC, aula y horario dados
$queryEliminarNovedades = $link->prepare("
   DELETE FROM novedades
WHERE RESERVA_AULA_ID_reserva IN (
    SELECT ra.ID_reserva
    FROM nrcs n
    INNER JOIN reserva_aula ra ON ra.NRCS_ID_NRC = n.ID_NRC
    WHERE n.PERIODOS_ID_periodo = $ID_periodo
);

");

$queryEliminarNovedades->bind_param("i", $ID_periodo);

if ($queryEliminarNovedades->execute()) {
    echo json_encode(['success' => true, 'message' => 'Novedades eliminadas con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar Novedades']);
}

// Eliminar reservas anteriores que coincidan con el NRC, aula y horario dados
$queryEliminarReservasEspecificas = $link->prepare("
 DELETE FROM reserva_aula
WHERE NRCS_ID_NRC IN (
    SELECT n.ID_NRC
    FROM nrcs n
    WHERE n.PERIODOS_ID_periodo = $ID_periodo
);

");

$queryEliminarReservasEspecificas->bind_param("i", $nrcID);

if ($queryEliminarReservasEspecificas->execute()) {
    echo json_encode(['success' => true, 'message' => 'Reservas específicas eliminadas con éxito']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar reservas específicas']);
}
