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
include 'config.php';
require_once "helpers.php";

// Obtener datos del NRC soltado
$id =  $_POST['id']; 
$horario = $_POST['horario']; 
$aula = $_POST['aula']; 
$estado = $_POST['estado'];
$dia = $_POST['dia'];

// Primero, obtener los IDs correspondientes a los horarios y aulas disponibles
$horarioDisponibleIDQuery = "SELECT ID_horario_disponible FROM horarios_disponibles hd 
                             INNER JOIN horarios h ON hd.HORARIOS_ID_horario = h.ID_horario 
                             WHERE h.ID_horario = $horario AND hd.PERIODOS_ID_periodo = 1";
$aulaDisponibleIDQuery = "SELECT ID_aula_disponible FROM aulas_disponibles ad 
                          INNER JOIN aulas a ON ad.AULAS_ID_aula = a.ID_aula 
                          WHERE a.ID_aula = $aula AND ad.PERIODOS_ID_periodo = 1";

$horarioDisponibleIDResult = mysqli_query($link, $horarioDisponibleIDQuery);
$aulaDisponibleIDResult = mysqli_query($link, $aulaDisponibleIDQuery);

// Verificar si los resultados de las consultas son válidos
if ($horarioDisponibleIDResult && $aulaDisponibleIDResult) {
    // Obtener los IDs de horario y aula disponibles
    $horarioDisponibleIDRow = mysqli_fetch_assoc($horarioDisponibleIDResult);
    $aulaDisponibleIDRow = mysqli_fetch_assoc($aulaDisponibleIDResult);

    if ($horarioDisponibleIDRow && $aulaDisponibleIDRow) {
        $horarioDisponibleID = $horarioDisponibleIDRow['ID_horario_disponible'];
        $aulaDisponibleID = $aulaDisponibleIDRow['ID_aula_disponible'];

        // Verificar si ya existe un registro con estos IDs
        $checkExistQuery = "SELECT * FROM aulas_horarios 
                            WHERE HORARIOS_DISPONIBLES_ID_horario_disponible = $horarioDisponibleID 
                            AND AULAS_DISPONIBLES_ID_aula_disponible = $aulaDisponibleID
							AND dia = '$dia'";
        $checkExistResult = mysqli_query($link, $checkExistQuery);

        // Verificar si la consulta fue exitosa
        if ($checkExistResult) {
            if (mysqli_num_rows($checkExistResult) == 0) {
                // Si no existe, insertar el nuevo registro
                $insertQuery = "INSERT INTO aulas_horarios (ID_aulas_horarios, HORARIOS_DISPONIBLES_ID_horario_disponible, AULAS_DISPONIBLES_ID_aula_disponible, estado, dia) 
                                VALUES ($id, $horarioDisponibleID, $aulaDisponibleID, '$estado', '$dia')";
                $insertResult = mysqli_query($link, $insertQuery);
                if($insertResult){
                    echo "Registro insertado con éxito.";
                } else {
                    echo "Error al insertar el registro.";
                }
            } else {
                echo "El registro ya existe, no se insertó.";
            }
        } else {
            echo "Error al verificar la existencia del registro.";
        }
    } else {
        echo "Error al obtener los IDs de horario o aula.";
    }
} else {
    echo "Error al ejecutar la consulta de IDs de horario o aula.";
}

?>
