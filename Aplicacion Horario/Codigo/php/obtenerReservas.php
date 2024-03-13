<?php
// Iniciar sesión
session_start();

// Verificar si el usuario no está autenticado
if (!isset($_SESSION['username'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: ../index.html");
    exit();
}
?>
<?php
// Incluir conexión a base de datos
include 'config.php';
require_once "helpers.php";


// Consultar la base de datos para obtener los NRCs
$query = "
	SELECT n.ID_NRC as nrc, a.ID_aula as aula, h.ID_horario as horario, ah.dia as dia
FROM reserva_aula r
INNER JOIN nrcs n ON r.NRCS_ID_NRC = n.ID_NRC
INNER JOIN aulas_horarios ah ON r.AULAS_HORARIOS_ID_aulas_horarios = ah.ID_aulas_horarios
INNER JOIN horarios_disponibles hd ON ah.HORARIOS_DISPONIBLES_ID_horario_disponible = hd.ID_horario_disponible
INNER JOIN aulas_disponibles ad ON ah.AULAS_DISPONIBLES_ID_aula_disponible = ad.ID_aula_disponible
INNER JOIN horarios h ON hd.HORARIOS_ID_horario = h.ID_horario
INNER JOIN aulas a ON ad.AULAS_ID_aula = a.ID_aula
WHERE n.PERIODOS_ID_periodo = 1
";
$result = mysqli_query($link, $query);

$reservas = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reservas[] = $row;
    }
}

// Configurar cabecera para devolver JSON
header('Content-Type: application/json');
echo json_encode($reservas);	