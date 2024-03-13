<?php
// Incluir conexión a base de datos
include 'config.php';
require_once "helpers.php";

// Obtener datos del formulario
$codigoNRC = $_POST['nrc'];
$aula = $_POST['aula'];
$horario = $_POST['horario'];
$dia = $_POST['dia'];

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


// Luego, encontrar el ID de aula_horario que coincida con el aula, horario y día
$queryBuscarID = $link->prepare("SELECT ID_aulas_horarios FROM aulas_horarios ah
INNER JOIN aulas_disponibles ad ON ah.AULAS_DISPONIBLES_ID_aula_disponible = ad.ID_aula_disponible
INNER JOIN horarios_disponibles hd ON ah.HORARIOS_DISPONIBLES_ID_horario_disponible = hd.ID_horario_disponible
INNER JOIN aulas a ON ad.AULAS_ID_aula = a.ID_aula
INNER JOIN horarios h ON hd.HORARIOS_ID_horario = h.ID_horario
WHERE a.ID_aula = ? AND h.ID_horario = ? AND ah.dia = ?");
$queryBuscarID->bind_param("sss", $aula, $horario, $dia);
$queryBuscarID->execute();
$resultado = $queryBuscarID->get_result();

if ($resultado->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => "No se encontró el ID de aula_horario correspondiente."]);
    exit;
}

$fila = $resultado->fetch_assoc();
$aulaHorarioID = $fila['ID_aulas_horarios'];


// Finalmente, insertar la reserva usando una consulta preparada
$queryInsertar = $link->prepare("INSERT INTO reserva_aula (NRCS_ID_NRC, AULAS_HORARIOS_ID_aulas_horarios) VALUES (?, ?)");
$queryInsertar->bind_param("ii", $nrcID, $aulaHorarioID);

if ($queryInsertar->execute()) {
    $response = ['success' => true, 'message' => 'Reserva agregada con éxito'];
} else {
    $error = $link->error;
    $response = ['success' => false, 'message' => "Error al agregar reserva: $error"];
}

header('Content-Type: application/json');
echo json_encode($response);
?>
