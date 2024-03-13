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
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$NRCS_ID_NRC = "";
$AULAS_HORARIOS_ID_aulas_horarios = "";

$NRCS_ID_NRC_err = "";
$AULAS_HORARIOS_ID_aulas_horarios_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $NRCS_ID_NRC = trim($_POST["NRCS_ID_NRC"]);
		$AULAS_HORARIOS_ID_aulas_horarios = trim($_POST["AULAS_HORARIOS_ID_aulas_horarios"]);
		

        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Something weird happened'); //something a user can understand
        }

        $vars = parse_columns('reserva_aula', $_POST);
        $stmt = $pdo->prepare("INSERT INTO reserva_aula (NRCS_ID_NRC,AULAS_HORARIOS_ID_aulas_horarios) VALUES (?,?)");

        if($stmt->execute([ $NRCS_ID_NRC,$AULAS_HORARIOS_ID_aulas_horarios  ])) {
                $stmt = null;
                header("location: reserva_aula-index.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="form-group">
                            <label>NRC</label>
                            <select class="form-control" id="NRCS_ID_NRC" name="NRCS_ID_NRC">
                                <?php
                                    // Tu consulta SQL aquí para obtener los NRC
                                    $sql = "SELECT ID_NRC, codigo FROM nrcs";
                                    $result = mysqli_query($link, $sql);
                                    while($row = mysqli_fetch_assoc($result)) {
                                        $selected = ($row["ID_NRC"] == $NRCS_ID_NRC) ? 'selected="selected"' : '';
                                        echo "<option value='" . $row["ID_NRC"] . "' $selected>" . $row["codigo"] . "</option>";
                                    }
                                ?>
                            </select>
                            <span class="form-text"><?php echo $NRCS_ID_NRC_err; ?></span>
                        </div>

                        <!-- Div para mostrar los detalles del NRC seleccionado -->
                        <div id="detallesNRC">
                        <?php
                            $sql = "SELECT 
                                    a.nombre AS 'asignatura', 
                                    d.nombre AS 'docente',
                                    c.nombre AS 'carrera',
                                    h.hora_inicio AS 'horario_i',
                                    h.hora_fin AS 'horario_f'
                                FROM 
                                    nrcs n
                                    INNER JOIN asignaturas a ON a.ID_asignatura = n.ASIGNATURAS_VIGENTES_ID_asignatura_vigente
                                    INNER JOIN docentes d ON d.ID_docente = n.DOCENTES_ACTIVOS_ID_docente_activo
                                    INNER JOIN carreras c ON c.ID_carrera = n.CARRERAS_VIGENTES_ID_carrera_vigente
                                    INNER JOIN horarios h ON h.ID_horario = n.HORARIOS_DISPONIBLES_ID_horario_disponible";

                            $result = mysqli_query($link, $sql);

                            if ($row = mysqli_fetch_assoc($result)) {
                                echo "<label>Asignatura: " . $row["asignatura"] . "</label><br>";
                                echo "<label>Docente: " . $row["docente"] . "</label><br>";
                                echo "<label>Carrera: " . $row["carrera"] . "</label><br>";
                                echo "<label>Horario: " . $row["horario_i"] . " a " . $row["horario_f"] . "</label><br>";
                            } else {
                                echo "<label>Información no encontrada</label>";
                            }
                            ?>


                        </div>

						<div class="form-group">
                                <label>Aula</label>
                                    <select class="form-control" id="AULAS_HORARIOS_ID_aulas_horarios" name="AULAS_HORARIOS_ID_aulas_horarios">
                                    <?php
                                    // Obtener el horario del NRC seleccionado
                                    $sql_horario_nrc = "SELECT horarios.ID_horario AS 'ID_horario', horarios.hora_inicio AS 'hora_inicio', horarios.hora_fin AS 'hora_fin'
                                                        FROM nrcs
                                                        INNER JOIN horarios ON nrcs.HORARIOS_DISPONIBLES_ID_horario_disponible = horarios.ID_horario
                                                        WHERE nrcs.ID_NRC = $NRCS_ID_NRC";

                                    $result_horario_nrc = mysqli_query($link, $sql_horario_nrc);

                                    if ($row_horario_nrc = mysqli_fetch_assoc($result_horario_nrc)) {
                                        $horario_inicio_nrc = $row_horario_nrc['hora_inicio'];
                                        $horario_fin_nrc = $row_horario_nrc['hora_fin'];

                                        // Consulta SQL para obtener las aulas con un horario que coincide con el del NRC seleccionado
                                        $sql_aulas = "SELECT 
                                                        ah.ID_aulas_horarios AS 'ID_aulas_horarios',
                                                        ah.dia AS 'dia',
                                                        h.hora_inicio AS 'horario_i',
                                                        h.hora_fin AS 'horario_f',
                                                        a.bloque AS 'bloque',
                                                        a.codigo AS 'aula',
                                                        ah.estado AS 'estado'
                                                    FROM 
                                                        aulas_horarios ah
                                                        INNER JOIN horarios h ON h.ID_horario = ah.HORARIOS_DISPONIBLES_ID_horario_disponible
                                                        INNER JOIN aulas a ON a.ID_aula = ah.AULAS_DISPONIBLES_ID_aula_disponible
                                                    WHERE 
                                                        h.hora_inicio = '$horario_inicio_nrc'
                                                        AND h.hora_fin = '$horario_fin_nrc'";

                                        $result_aulas = mysqli_query($link, $sql_aulas);

                                        while ($row_aula = mysqli_fetch_assoc($result_aulas)) {
                                            // Convertir el estado a 'disponible' o 'ocupada'
                                            $estado = ($row_aula['estado'] == 0) ? 'disponible' : 'ocupada';

                                            // Mostrar opciones del select con los detalles del aula y el estado
                                            echo '<option value="' . $row_aula["ID_aulas_horarios"] . '">' .
                                                    $row_aula["dia"] . ' - ' . $row_aula["horario_i"] . ' a ' . $row_aula["horario_f"] . ' - ' . $row_aula["bloque"] . ' - ' . $row_aula["aula"] . ' (' . $estado . ')' .
                                                '</option>';
                                        }
                                    } else {
                                        echo "<option value=''>No hay aulas disponibles para este horario</option>";
                                    }
                                    ?>



                                    </select>
                                <span class="form-text"><?php echo $AULAS_HORARIOS_ID_aulas_horarios_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="reserva_aula-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>