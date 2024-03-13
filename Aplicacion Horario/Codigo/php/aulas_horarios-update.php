<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$HORARIOS_DISPONIBLES_ID_horario_disponible = "";
$AULAS_DISPONIBLES_ID_aula_disponible = "";
$estado = "";
$dia = "";

$HORARIOS_DISPONIBLES_ID_horario_disponible_err = "";
$AULAS_DISPONIBLES_ID_aula_disponible_err = "";
$estado_err = "";
$dia_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_aulas_horarios"]) && !empty($_POST["ID_aulas_horarios"])){
    // Get hidden input value
    $ID_aulas_horarios = $_POST["ID_aulas_horarios"];

    $HORARIOS_DISPONIBLES_ID_horario_disponible = trim($_POST["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
		$AULAS_DISPONIBLES_ID_aula_disponible = trim($_POST["AULAS_DISPONIBLES_ID_aula_disponible"]);
		$estado = trim($_POST["estado"]);
		$dia = trim($_POST["dia"]);
		

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

    $vars = parse_columns('aulas_horarios', $_POST);
    $stmt = $pdo->prepare("UPDATE aulas_horarios SET HORARIOS_DISPONIBLES_ID_horario_disponible=?,AULAS_DISPONIBLES_ID_aula_disponible=?,estado=?,dia=? WHERE ID_aulas_horarios=?");

    if(!$stmt->execute([ $HORARIOS_DISPONIBLES_ID_horario_disponible,$AULAS_DISPONIBLES_ID_aula_disponible,$estado,$dia,$ID_aulas_horarios  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: aulas_horarios-read.php?ID_aulas_horarios=$ID_aulas_horarios");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_aulas_horarios"] = trim($_GET["ID_aulas_horarios"]);
    if(isset($_GET["ID_aulas_horarios"]) && !empty($_GET["ID_aulas_horarios"])){
        // Get URL parameter
        $ID_aulas_horarios =  trim($_GET["ID_aulas_horarios"]);

        // Prepare a select statement
        $sql = "SELECT * FROM aulas_horarios WHERE ID_aulas_horarios = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_aulas_horarios;

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

                    $HORARIOS_DISPONIBLES_ID_horario_disponible = htmlspecialchars($row["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
					$AULAS_DISPONIBLES_ID_aula_disponible = htmlspecialchars($row["AULAS_DISPONIBLES_ID_aula_disponible"]);
					$estado = htmlspecialchars($row["estado"]);
					$dia = htmlspecialchars($row["dia"]);
					

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
                                    <select class="form-control" id="HORARIOS_DISPONIBLES_ID_horario_disponible" name="HORARIOS_DISPONIBLES_ID_horario_disponible">
                                    <?php
                                        $sql = "SELECT *,ID_horario_disponible FROM horarios_disponibles";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_horario_disponible"]);
                                            $value = implode(" | ", $duprow);
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
                                <label>Aula</label>
                                    <select class="form-control" id="AULAS_DISPONIBLES_ID_aula_disponible" name="AULAS_DISPONIBLES_ID_aula_disponible">
                                    <?php
                                        $sql = "SELECT *,ID_aula_disponible FROM aulas_disponibles";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_aula_disponible"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_aula_disponible"] == $AULAS_DISPONIBLES_ID_aula_disponible){
                                            echo '<option value="' . "$row[ID_aula_disponible]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_aula_disponible]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $AULAS_DISPONIBLES_ID_aula_disponible_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                                <span class="form-text"><?php echo $estado_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Día</label>
                                <input type="text" name="dia" maxlength="45"class="form-control" value="<?php echo $dia; ?>">
                                <span class="form-text"><?php echo $dia_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_aulas_horarios" value="<?php echo $ID_aulas_horarios; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="aulas_horarios-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
