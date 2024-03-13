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
	SELECT 
	ad.AULAS_ID_aula AS id, 
	a.codigo AS codigo,
    a.bloque AS bloque, 
	a.piso AS piso
FROM aulas_disponibles ad
INNER JOIN aulas a ON ad.AULAS_ID_aula = a.ID_aula 
WHERE ad.PERIODOS_ID_periodo = 1
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
