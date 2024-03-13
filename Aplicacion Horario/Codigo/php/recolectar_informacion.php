<?php
// Incluir conexión a base de datos
include 'config.php';
require_once "helpers.php";


// Consultar la base de datos para obtener los NRCs
$query = "
	SELECT 
    n.id_nrc AS id, 
    a.nombre AS asignatura, 
    d.nombre AS docente, 
    h.ID_horario AS horario, 
    c.nombre AS carrera, 
    p.nombre AS periodo, 
    n.codigo,  
    n.nivel, 
    n.horas_semanales 
FROM nrcs n
INNER JOIN asignaturas_vigentes av ON n.ASIGNATURAS_VIGENTES_ID_asignatura_vigente = av.ID_asignatura_vigente
INNER JOIN docentes_activos da ON n.DOCENTES_ACTIVOS_ID_docente_activo = da.ID_docente_activo
INNER JOIN horarios_disponibles hd ON n.HORARIOS_DISPONIBLES_ID_horario_disponible = hd.ID_horario_disponible
INNER JOIN carreras_vigentes cv ON n.CARRERAS_VIGENTES_ID_carrera_vigente = cv.ID_carrera_vigente
INNER JOIN periodos p ON n.PERIODOS_ID_periodo = p.ID_periodo
INNER JOIN asignaturas a ON av.ASIGNATURAS_ID_asignatura = a.ID_asignatura
INNER JOIN docentes d ON da.DOCENTES_ID_docente = d.ID_docente
INNER JOIN horarios h ON hd.HORARIOS_ID_horario = h.ID_horario
INNER JOIN carreras c ON cv.CARRERAS_ID_carrera = c.ID_carrera
WHERE n.PERIODOS_ID_periodo = 1

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
