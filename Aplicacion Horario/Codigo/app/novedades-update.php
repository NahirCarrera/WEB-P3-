<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$RESERVA_AULA_ID_reserva = "";
$fecha = "";
$descripción = "";

$RESERVA_AULA_ID_reserva_err = "";
$fecha_err = "";
$descripción_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_novedad"]) && !empty($_POST["ID_novedad"])){
    // Get hidden input value
    $ID_novedad = $_POST["ID_novedad"];

    $RESERVA_AULA_ID_reserva = trim($_POST["RESERVA_AULA_ID_reserva"]);
		$fecha = trim($_POST["fecha"]);
		$descripción = trim($_POST["descripción"]);
		

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

    $vars = parse_columns('novedades', $_POST);
    $stmt = $pdo->prepare("UPDATE novedades SET RESERVA_AULA_ID_reserva=?,fecha=?,descripción=? WHERE ID_novedad=?");

    if(!$stmt->execute([ $RESERVA_AULA_ID_reserva,$fecha,$descripción,$ID_novedad  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: novedades-read.php?ID_novedad=$ID_novedad");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_novedad"] = trim($_GET["ID_novedad"]);
    if(isset($_GET["ID_novedad"]) && !empty($_GET["ID_novedad"])){
        // Get URL parameter
        $ID_novedad =  trim($_GET["ID_novedad"]);

        // Prepare a select statement
        $sql = "SELECT * FROM novedades WHERE ID_novedad = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_novedad;

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

                    $RESERVA_AULA_ID_reserva = htmlspecialchars($row["RESERVA_AULA_ID_reserva"]);
					$fecha = htmlspecialchars($row["fecha"]);
					$descripción = htmlspecialchars($row["descripción"]);
					

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
                                <label>Reserva</label>
                                    <select class="form-control" id="RESERVA_AULA_ID_reserva" name="RESERVA_AULA_ID_reserva">
                                    <?php
                                        $sql = "SELECT *,ID_reserva FROM reserva_aula";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_reserva"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_reserva"] == $RESERVA_AULA_ID_reserva){
                                            echo '<option value="' . "$row[ID_reserva]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_reserva]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $RESERVA_AULA_ID_reserva_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha</label>
                                <input type="date" name="fecha" class="form-control" value="<?php echo $fecha; ?>">
                                <span class="form-text"><?php echo $fecha_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Novedad</label>
                                <input type="text" name="descripción" maxlength="200"class="form-control" value="<?php echo $descripción; ?>">
                                <span class="form-text"><?php echo $descripción_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_novedad" value="<?php echo $ID_novedad; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="novedades-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
