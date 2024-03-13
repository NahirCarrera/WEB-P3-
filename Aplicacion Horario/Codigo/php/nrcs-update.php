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
$ASIGNATURAS_VIGENTES_ID_asignatura_vigente = "";
$DOCENTES_ACTIVOS_ID_docente_activo = "";
$HORARIOS_DISPONIBLES_ID_horario_disponible = "";
$CARRERAS_VIGENTES_ID_carrera_vigente = "";
$PERIODOS_ID_periodo = "";
$codigo = "";
$nivel = "";
$horas_semanales = "";

$ASIGNATURAS_VIGENTES_ID_asignatura_vigente_err = "";
$DOCENTES_ACTIVOS_ID_docente_activo_err = "";
$HORARIOS_DISPONIBLES_ID_horario_disponible_err = "";
$CARRERAS_VIGENTES_ID_carrera_vigente_err = "";
$PERIODOS_ID_periodo_err = "";
$codigo_err = "";
$nivel_err = "";
$horas_semanales_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_NRC"]) && !empty($_POST["ID_NRC"])){
    // Get hidden input value
    $ID_NRC = $_POST["ID_NRC"];

    $ASIGNATURAS_VIGENTES_ID_asignatura_vigente = trim($_POST["ASIGNATURAS_VIGENTES_ID_asignatura_vigente"]);
		$DOCENTES_ACTIVOS_ID_docente_activo = trim($_POST["DOCENTES_ACTIVOS_ID_docente_activo"]);
		$HORARIOS_DISPONIBLES_ID_horario_disponible = trim($_POST["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
		$CARRERAS_VIGENTES_ID_carrera_vigente = trim($_POST["CARRERAS_VIGENTES_ID_carrera_vigente"]);
		$PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
		$codigo = trim($_POST["codigo"]);
		$nivel = trim($_POST["nivel"]);
		$horas_semanales = trim($_POST["horas_semanales"]);
		

    // Prepare an update statement
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
        exit('Something weird happened');
    }

    $vars = parse_columns('nrcs', $_POST);
    $stmt = $pdo->prepare("UPDATE nrcs SET ASIGNATURAS_VIGENTES_ID_asignatura_vigente=?,DOCENTES_ACTIVOS_ID_docente_activo=?,HORARIOS_DISPONIBLES_ID_horario_disponible=?,CARRERAS_VIGENTES_ID_carrera_vigente=?,PERIODOS_ID_periodo=?,codigo=?,nivel=?,horas_semanales=? WHERE ID_NRC=?");

    if(!$stmt->execute([ $ASIGNATURAS_VIGENTES_ID_asignatura_vigente,$DOCENTES_ACTIVOS_ID_docente_activo,$HORARIOS_DISPONIBLES_ID_horario_disponible,$CARRERAS_VIGENTES_ID_carrera_vigente,$PERIODOS_ID_periodo,$codigo,$nivel,$horas_semanales,$ID_NRC  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: nrcs-read.php?ID_NRC=$ID_NRC");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_NRC"] = trim($_GET["ID_NRC"]);
    if(isset($_GET["ID_NRC"]) && !empty($_GET["ID_NRC"])){
        // Get URL parameter
        $ID_NRC =  trim($_GET["ID_NRC"]);

        // Prepare a select statement
        $sql = "SELECT * FROM nrcs WHERE ID_NRC = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_NRC;

            // Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $ASIGNATURAS_VIGENTES_ID_asignatura_vigente = htmlspecialchars($row["ASIGNATURAS_VIGENTES_ID_asignatura_vigente"]);
					$DOCENTES_ACTIVOS_ID_docente_activo = htmlspecialchars($row["DOCENTES_ACTIVOS_ID_docente_activo"]);
					$HORARIOS_DISPONIBLES_ID_horario_disponible = htmlspecialchars($row["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
					$CARRERAS_VIGENTES_ID_carrera_vigente = htmlspecialchars($row["CARRERAS_VIGENTES_ID_carrera_vigente"]);
					$PERIODOS_ID_periodo = htmlspecialchars($row["PERIODOS_ID_periodo"]);
					$codigo = htmlspecialchars($row["codigo"]);
					$nivel = htmlspecialchars($row["nivel"]);
					$horas_semanales = htmlspecialchars($row["horas_semanales"]);
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group">
                                <label>Asignatura</label>
                                    <select class="form-control" id="ASIGNATURAS_VIGENTES_ID_asignatura_vigente" name="ASIGNATURAS_VIGENTES_ID_asignatura_vigente">
                                    <?php
                                        $sql = "SELECT nombre FROM asignaturas";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_asignatura_vigente"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_asignatura_vigente"] == $ASIGNATURAS_VIGENTES_ID_asignatura_vigente){
                                            echo '<option value="' . "$row[ID_asignatura_vigente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_asignatura_vigente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $ASIGNATURAS_VIGENTES_ID_asignatura_vigente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Docente</label>
                                    <select class="form-control" id="DOCENTES_ACTIVOS_ID_docente_activo" name="DOCENTES_ACTIVOS_ID_docente_activo">
                                    <?php
                                        $sql = "SELECT nombre FROM docentes";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_docente_activo"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_docente_activo"] == $DOCENTES_ACTIVOS_ID_docente_activo){
                                            echo '<option value="' . "$row[ID_docente_activo]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_docente_activo]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $DOCENTES_ACTIVOS_ID_docente_activo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horario</label>
                                    <select class="form-control" id="HORARIOS_DISPONIBLES_ID_horario_disponible" name="HORARIOS_DISPONIBLES_ID_horario_disponible">
                                    <?php
                                        $sql = "SELECT hora_inicio, hora_fin FROM horarios";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_horario_disponible"]);
                                            $value = implode(" - ", $duprow);
                                            if ($row["ID_horario_disponible"] == $HORARIOS_DISPONIBLES_ID_horario_disponible){
                                            echo '<option value="' . "$row[ID_horario_disponible]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_horario_disponible]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $HORARIOS_DISPONIBLES_ID_horario_disponible_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Carrera</label>
                                    <select class="form-control" id="CARRERAS_VIGENTES_ID_carrera_vigente" name="CARRERAS_VIGENTES_ID_carrera_vigente">
                                    <?php
                                        $sql = "SELECT nombre FROM carreras";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_carrera_vigente"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_carrera_vigente"] == $CARRERAS_VIGENTES_ID_carrera_vigente){
                                            echo '<option value="' . "$row[ID_carrera_vigente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_carrera_vigente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $CARRERAS_VIGENTES_ID_carrera_vigente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Periodo</label>
                                    <select class="form-control" id="PERIODOS_ID_periodo" name="PERIODOS_ID_periodo">
                                    <?php
                                        $sql = "SELECT ID_periodo, codigo, nombre FROM periodos";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_periodo"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_periodo"] == $PERIODOS_ID_periodo){
                                            echo '<option value="' . "$row[ID_periodo]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_periodo]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $PERIODOS_ID_periodo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>NRC</label>
                                <input type="number" name="codigo" class="form-control" value="<?php echo $codigo; ?>">
                                <span class="form-text"><?php echo $codigo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nivel</label>
                                <input type="number" name="nivel" class="form-control" value="<?php echo $nivel; ?>" max = 10 min = 1>
                                <span class="form-text"><?php echo $nivel_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horas semanales</label>
                                <input type="number" name="horas_semanales" class="form-control" value="<?php echo $horas_semanales; ?>" max = 10 min=1>
                                <span class="form-text"><?php echo $horas_semanales_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_NRC" value="<?php echo $ID_NRC; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="nrcs-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
