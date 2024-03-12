<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values

$DEPARTAMENTOS_ID_departamento = "";
$codigo = "";
$nombre = "";

$DEPARTAMENTOS_ID_departamento_err = "";
$codigo_err = "";
$nombre_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_asignatura"]) && !empty($_POST["ID_asignatura"])){
    // Get hidden input value
    $ID_asignatura = $_POST["ID_asignatura"];

    $DEPARTAMENTOS_ID_departamento = trim($_POST["DEPARTAMENTOS_ID_departamento"]);
		$codigo = trim($_POST["codigo"]);
		$nombre = trim($_POST["nombre"]);
		

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

    $vars = parse_columns('asignaturas', $_POST);
    $stmt = $pdo->prepare("UPDATE asignaturas SET DEPARTAMENTOS_ID_departamento=?,codigo=?,nombre=? WHERE ID_asignatura=?");

    if(!$stmt->execute([ $DEPARTAMENTOS_ID_departamento,$codigo,$nombre,$ID_asignatura])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: asignaturas-read.php?ID_asignatura=$ID_asignatura");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_asignatura"] = trim($_GET["ID_asignatura"]);
    if(isset($_GET["ID_asignatura"]) && !empty($_GET["ID_asignatura"])){
        // Get URL parameter
        $ID_asignatura =  trim($_GET["ID_asignatura"]);

        // Prepare a select statement
        $sql = "SELECT * FROM asignaturas WHERE ID_asignatura = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_asignatura;

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

                    $DEPARTAMENTOS_ID_departamento = htmlspecialchars($row["DEPARTAMENTOS_ID_departamento"]);
					$codigo = htmlspecialchars($row["codigo"]);
					$nombre = htmlspecialchars($row["nombre"]);
					

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
                                <label>Departamento</label>
                                    <select class="form-control" id="DEPARTAMENTOS_ID_departamento" name="DEPARTAMENTOS_ID_departamento">
                                    <?php
                                        $sql = "SELECT ID_departamento, codigo, nombre FROM departamentos";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_departamento"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_departamento"] == $DEPARTAMENTOS_ID_departamento){
                                            echo '<option value="' . "$row[ID_departamento]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_departamento]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $DEPARTAMENTOS_ID_departamento_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Código</label>
                                <input type="text" name="codigo" maxlength="10"class="form-control" value="<?php echo $codigo; ?>">
                                <span class="form-text"><?php echo $codigo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Asignatura</label>
                                <input type="text" name="nombre" maxlength="100"class="form-control" value="<?php echo $nombre; ?>">
                                <span class="form-text"><?php echo $nombre_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_asignatura" value="<?php echo $ID_asignatura; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="asignaturas-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>