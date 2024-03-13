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
$DOCENTES_ID_docente = "";
$PERIODOS_ID_periodo = "";
$jornada = "";
$horas_presenciales = "";

$DOCENTES_ID_docente_err = "";
$PERIODOS_ID_periodo_err = "";
$jornada_err = "";
$horas_presenciales_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_docente_activo"]) && !empty($_POST["ID_docente_activo"])){
    // Get hidden input value
    $ID_docente_activo = $_POST["ID_docente_activo"];

    $DOCENTES_ID_docente = trim($_POST["DOCENTES_ID_docente"]);
		$PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
		$jornada = trim($_POST["jornada"]);
		$horas_presenciales = trim($_POST["horas_presenciales"]);
		

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

    $vars = parse_columns('docentes_activos', $_POST);
    $stmt = $pdo->prepare("UPDATE docentes_activos SET DOCENTES_ID_docente=?,PERIODOS_ID_periodo=?,jornada=?,horas_presenciales=? WHERE ID_docente_activo=?");

    if(!$stmt->execute([ $DOCENTES_ID_docente,$PERIODOS_ID_periodo,$jornada,$horas_presenciales,$ID_docente_activo  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: docentes_activos-read.php?ID_docente_activo=$ID_docente_activo");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_docente_activo"] = trim($_GET["ID_docente_activo"]);
    if(isset($_GET["ID_docente_activo"]) && !empty($_GET["ID_docente_activo"])){
        // Get URL parameter
        $ID_docente_activo =  trim($_GET["ID_docente_activo"]);

        // Prepare a select statement
        $sql = "SELECT * FROM docentes_activos WHERE ID_docente_activo = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_docente_activo;

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

                    $DOCENTES_ID_docente = htmlspecialchars($row["DOCENTES_ID_docente"]);
					$PERIODOS_ID_periodo = htmlspecialchars($row["PERIODOS_ID_periodo"]);
					$jornada = htmlspecialchars($row["jornada"]);
					$horas_presenciales = htmlspecialchars($row["horas_presenciales"]);
					

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
                                <label>Docente</label>
                                    <select class="form-control" id="DOCENTES_ID_docente" name="DOCENTES_ID_docente">
                                    <?php
                                        $sql = "SELECT ID_docente, nombre FROM docentes";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_docente"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_docente"] == $DOCENTES_ID_docente){
                                            echo '<option value="' . "$row[ID_docente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_docente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $DOCENTES_ID_docente_err; ?></span>
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
                                <label for="jornada">Jornada</label>
                                <select name="jornada" id="jornada" class="form-control" onchange="actualizarHorasMax()">
                                    <option value="Tiempo completo"<?php if ($jornada == "Tiempo completo") echo " selected"; ?>>Tiempo completo</option>
                                    <option value="Medio tiempo"<?php if ($jornada == "Medio tiempo") echo " selected"; ?>>Medio tiempo</option>
                                    <option value="Por contrato"<?php if ($jornada == "Por contrato") echo " selected"; ?>>Por contrato</option>
                                    <option value="Por temporada"<?php if ($jornada == "Por temporada") echo " selected"; ?>>Por temporada</option>
                                </select>
                                <span class="form-text"><?php echo $jornada_err; ?></span>
                            </div>

                            <div class="form-group">
                                <label>Horas asignadas</label>
                                <input type="number" name="horas_presenciales" id="horas_presenciales" class="form-control" value="<?php echo $horas_presenciales; ?>" min="1" onkeydown="return false;">
                                <span class="form-text"><?php echo $horas_presenciales_err; ?></span>
                            </div>

                            <script>
                                function actualizarHorasMax() {
                                    var jornada = document.getElementById("jornada").value;
                                    var horasInput = document.getElementById("horas_presenciales");

                                    // Establecer el valor máximo según la jornada seleccionada
                                    switch (jornada) {
                                        case "Tiempo completo":
                                            horasInput.max = 40; // Ejemplo: 40 horas máximo para tiempo completo
                                            horasInput.value = 40; 
                                            break;
                                        case "Medio tiempo":
                                            horasInput.max = 25; // Ejemplo: 20 horas máximo para medio tiempo
                                            horasInput.value = 25;
                                            break;
                                        case "Por contrato":
                                            horasInput.max = 20; // Ejemplo: 30 horas máximo para contrato
                                            horasInput.value = 20;
                                            break;
                                        case "Por temporada":
                                            horasInput.max = 20; // Ejemplo: 25 horas máximo para temporada
                                            horasInput.value = 20;
                                            break;
                                        
                                    }
                                }
                                
                                // Llamar a la función al cargar la página para establecer el valor máximo inicial
                                window.onload = actualizarHorasMax;
                            </script>

                        <input type="hidden" name="ID_docente_activo" value="<?php echo $ID_docente_activo; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="docentes_activos-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
