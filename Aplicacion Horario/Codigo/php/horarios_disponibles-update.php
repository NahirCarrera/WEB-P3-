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
$HORARIOS_ID_horario = "";
$PERIODOS_ID_periodo = "";

$HORARIOS_ID_horario_err = "";
$PERIODOS_ID_periodo_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_horario_disponible"]) && !empty($_POST["ID_horario_disponible"])){
    // Get hidden input value
    $ID_horario_disponible = $_POST["ID_horario_disponible"];

    $HORARIOS_ID_horario = trim($_POST["HORARIOS_ID_horario"]);
		$PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
		

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

    $vars = parse_columns('horarios_disponibles', $_POST);
    $stmt = $pdo->prepare("UPDATE horarios_disponibles SET HORARIOS_ID_horario=?,PERIODOS_ID_periodo=? WHERE ID_horario_disponible=?");

    if(!$stmt->execute([ $HORARIOS_ID_horario,$PERIODOS_ID_periodo,$ID_horario_disponible  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: horarios_disponibles-read.php?ID_horario_disponible=$ID_horario_disponible");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_horario_disponible"] = trim($_GET["ID_horario_disponible"]);
    if(isset($_GET["ID_horario_disponible"]) && !empty($_GET["ID_horario_disponible"])){
        // Get URL parameter
        $ID_horario_disponible =  trim($_GET["ID_horario_disponible"]);

        // Prepare a select statement
        $sql = "SELECT * FROM horarios_disponibles WHERE ID_horario_disponible = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_horario_disponible;

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

                    $HORARIOS_ID_horario = htmlspecialchars($row["HORARIOS_ID_horario"]);
					$PERIODOS_ID_periodo = htmlspecialchars($row["PERIODOS_ID_periodo"]);
					

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
                                <label>Horario</label>
                                    <select class="form-control" id="HORARIOS_ID_horario" name="HORARIOS_ID_horario">
                                    <?php
                                        $sql = "SELECT *,ID_horario FROM horarios";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_horario"]);
                                            $value = implode(" - ", $duprow);
                                            if ($row["ID_horario"] == $HORARIOS_ID_horario){
                                            echo '<option value="' . "$row[ID_horario]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_horario]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $HORARIOS_ID_horario_err; ?></span>
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

                        <input type="hidden" name="ID_horario_disponible" value="<?php echo $ID_horario_disponible; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="horarios_disponibles-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
