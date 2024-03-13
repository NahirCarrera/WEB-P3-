<?php
// Incluir conexión a base de datos
include 'config.php';
require_once "helpers.php";

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
// Consultar la base de datos para obtener los NRCs
$query = "
	SELECT 
	ad.AULAS_ID_aula AS id, 
	a.codigo AS codigo,
    a.bloque AS bloque, 
	a.piso AS piso
FROM aulas_disponibles ad
INNER JOIN aulas a ON ad.AULAS_ID_aula = a.ID_aula 
WHERE ad.PERIODOS_ID_periodo = $ID_periodo
";
$result = mysqli_query($link, $query);

$nrcs = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $nrcs[] = $row;
    }
}

// Configurar cabecera para devolver JSON
header('Content-Type: application/json');
echo json_encode($nrcs);
