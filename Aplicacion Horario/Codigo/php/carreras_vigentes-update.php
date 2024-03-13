<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$PERIODOS_ID_periodo = "";
$CARRERAS_ID_carrera = "";

$PERIODOS_ID_periodo_err = "";
$CARRERAS_ID_carrera_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_carrera_vigente"]) && !empty($_POST["ID_carrera_vigente"])){
    // Get hidden input value
    $ID_carrera_vigente = $_POST["ID_carrera_vigente"];

    $PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
	$CARRERAS_ID_carrera = trim($_POST["CARRERAS_ID_carrera"]);
		

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

    $vars = parse_columns('carreras_vigentes', $_POST);
    $stmt = $pdo->prepare("UPDATE carreras_vigentes SET PERIODOS_ID_periodo=?,CARRERAS_ID_carrera=? WHERE ID_carrera_vigente=?");

    if(!$stmt->execute([ $PERIODOS_ID_periodo,$CARRERAS_ID_carrera,$ID_carrera_vigente  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: carreras_vigentes-read.php?ID_carrera_vigente=$ID_carrera_vigente");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_carrera_vigente"] = trim($_GET["ID_carrera_vigente"]);
    if(isset($_GET["ID_carrera_vigente"]) && !empty($_GET["ID_carrera_vigente"])){
        // Get URL parameter
        $ID_carrera_vigente =  trim($_GET["ID_carrera_vigente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM carreras_vigentes WHERE ID_carrera_vigente = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_carrera_vigente;

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
					$CARRERAS_ID_carrera = htmlspecialchars($row["CARRERAS_ID_carrera"]);
					

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
                                            $value = implode(" | ", $duprow);
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
                                <label>Carrera</label>
                                    <select class="form-control" id="CARRERAS_ID_carrera" name="CARRERAS_ID_carrera">
                                    <?php
                                        $sql = "SELECT *,ID_carrera FROM carreras";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_carrera"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_carrera"] == $CARRERAS_ID_carrera){
                                            echo '<option value="' . "$row[ID_carrera]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_carrera]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $CARRERAS_ID_carrera_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_carrera_vigente" value="<?php echo $ID_carrera_vigente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="carreras_vigentes-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
