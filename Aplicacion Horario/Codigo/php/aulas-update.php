<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$codigo = "";
$bloque = "";
$piso = "";

$codigo_err = "";
$bloque_err = "";
$piso_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_aula"]) && !empty($_POST["ID_aula"])){
    // Get hidden input value
    $ID_aula = $_POST["ID_aula"];

    $codigo = trim($_POST["codigo"]);
		$bloque = trim($_POST["bloque"]);
		$piso = trim($_POST["piso"]);
		

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

    $vars = parse_columns('aulas', $_POST);
    $stmt = $pdo->prepare("UPDATE aulas SET codigo=?,bloque=?,piso=? WHERE ID_aula=?");

    if(!$stmt->execute([ $codigo,$bloque,$piso,$ID_aula  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: aulas-read.php?ID_aula=$ID_aula");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_aula"] = trim($_GET["ID_aula"]);
    if(isset($_GET["ID_aula"]) && !empty($_GET["ID_aula"])){
        // Get URL parameter
        $ID_aula =  trim($_GET["ID_aula"]);

        // Prepare a select statement
        $sql = "SELECT * FROM aulas WHERE ID_aula = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_aula;

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
					$bloque = htmlspecialchars($row["bloque"]);
					$piso = htmlspecialchars($row["piso"]);
					

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
                                <label>Aula</label>
                                <input type="text" name="codigo" maxlength="4"class="form-control" value="<?php echo $codigo; ?>">
                                <span class="form-text"><?php echo $codigo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Bloque</label>
                                <input type="text" name="bloque" maxlength="1"class="form-control" value="<?php echo $bloque; ?>">
                                <span class="form-text"><?php echo $bloque_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Piso</label>
                                <input type="number" name="piso" class="form-control" value="<?php echo $piso; ?>">
                                <span class="form-text"><?php echo $piso_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_aula" value="<?php echo $ID_aula; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="aulas-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
