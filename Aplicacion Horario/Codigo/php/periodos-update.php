<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$codigo = "";
$nombre = "";
$fecha_inicio = "";
$fecha_fin = "";

$codigo_err = "";
$nombre_err = "";
$fecha_inicio_err = "";
$fecha_fin_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_periodo"]) && !empty($_POST["ID_periodo"])){
    // Get hidden input value
    $ID_periodo = $_POST["ID_periodo"];

    $codigo = trim($_POST["codigo"]);
		$nombre = trim($_POST["nombre"]);
		$fecha_inicio = trim($_POST["fecha_inicio"]);
		$fecha_fin = trim($_POST["fecha_fin"]);
		

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

    $vars = parse_columns('periodos', $_POST);
    $stmt = $pdo->prepare("UPDATE periodos SET codigo=?,nombre=?,fecha_inicio=?,fecha_fin=? WHERE ID_periodo=?");

    if(!$stmt->execute([ $codigo,$nombre,$fecha_inicio,$fecha_fin,$ID_periodo  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: periodos-read.php?ID_periodo=$ID_periodo");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_periodo"] = trim($_GET["ID_periodo"]);
    if(isset($_GET["ID_periodo"]) && !empty($_GET["ID_periodo"])){
        // Get URL parameter
        $ID_periodo =  trim($_GET["ID_periodo"]);

        // Prepare a select statement
        $sql = "SELECT * FROM periodos WHERE ID_periodo = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_periodo;

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

                    $codigo = htmlspecialchars($row["codigo"]);
					$nombre = htmlspecialchars($row["nombre"]);
					$fecha_inicio = htmlspecialchars($row["fecha_inicio"]);
					$fecha_fin = htmlspecialchars($row["fecha_fin"]);
					

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
                                <label>Código</label>
                                <input type="text" name="codigo" maxlength="6"class="form-control" value="<?php echo $codigo; ?>">
                                <span class="form-text"><?php echo $codigo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Periodo</label>
                                <input type="text" name="nombre" maxlength="45"class="form-control" value="<?php echo $nombre; ?>">
                                <span class="form-text"><?php echo $nombre_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fecha_inicio; ?>">
                                <span class="form-text"><?php echo $fecha_inicio_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Fecha de finalización</label>
                                <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fecha_fin; ?>">
                                <span class="form-text"><?php echo $fecha_fin_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_periodo" value="<?php echo $ID_periodo; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="periodos-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
