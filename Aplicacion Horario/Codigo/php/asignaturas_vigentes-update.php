<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$PERIODOS_ID_periodo = "";
$ASIGNATURAS_ID_asignatura = "";

$PERIODOS_ID_periodo_err = "";
$ASIGNATURAS_ID_asignatura_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_asignatura_vigente"]) && !empty($_POST["ID_asignatura_vigente"])){
    // Get hidden input value
    $ID_asignatura_vigente = $_POST["ID_asignatura_vigente"];

    $PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
    $ASIGNATURAS_ID_asignatura = trim($_POST["ASIGNATURAS_ID_asignatura"]);
		

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

    $vars = parse_columns('asignaturas_vigentes', $_POST);
    $stmt = $pdo->prepare("UPDATE asignaturas_vigentes SET PERIODOS_ID_periodo=?,ASIGNATURAS_ID_asignatura=? WHERE ID_asignatura_vigente=?");

    if(!$stmt->execute([ $PERIODOS_ID_periodo,$ASIGNATURAS_ID_asignatura,$ID_asignatura_vigente  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: asignaturas_vigentes-read.php?ID_asignatura_vigente=$ID_asignatura_vigente");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_asignatura_vigente"] = trim($_GET["ID_asignatura_vigente"]);
    if(isset($_GET["ID_asignatura_vigente"]) && !empty($_GET["ID_asignatura_vigente"])){
        // Get URL parameter
        $ID_asignatura_vigente =  trim($_GET["ID_asignatura_vigente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM asignaturas_vigentes WHERE ID_asignatura_vigente = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_asignatura_vigente;

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

                    $PERIODOS_ID_periodo = htmlspecialchars($row["PERIODOS_ID_periodo"]);
					$ASIGNATURAS_ID_asignatura = htmlspecialchars($row["ASIGNATURAS_ID_asignatura"]);
					

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
                                <label>Asignatura</label>
                                    <select class="form-control" id="ASIGNATURAS_ID_asignatura" name="ASIGNATURAS_ID_asignatura">
                                    <?php
                                        $sql = "SELECT ID_asignatura, codigo, nombre FROM asignaturas";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_asignatura"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_asignatura"] == $ASIGNATURAS_ID_asignatura){
                                            echo '<option value="' . "$row[ID_asignatura]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_asignatura]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $ASIGNATURAS_ID_asignatura_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_asignatura_vigente" value="<?php echo $ID_asignatura_vigente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="asignaturas_vigentes-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
