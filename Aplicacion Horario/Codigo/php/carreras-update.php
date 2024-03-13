<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre = "";
$nombre_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingresa el nombre de la carrera.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Check input errors before updating the database
    if (empty($nombre_err)) {
        // Get hidden input value
        $ID_carrera = $_POST["ID_carrera"];

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

        $vars = parse_columns('carreras', $_POST);
        $stmt = $pdo->prepare("UPDATE carreras SET nombre=? WHERE ID_carrera=?");

        if (!$stmt->execute([$nombre, $ID_carrera])) {
            echo "Something went wrong. Please try again later.";
            header("location: error.php");
            exit();
        } else {
            $stmt = null;
            header("location: carreras-read.php?ID_carrera=$ID_carrera");
            exit();
        }
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["ID_carrera"] = trim($_GET["ID_carrera"]);
    if (isset($_GET["ID_carrera"]) && !empty($_GET["ID_carrera"])) {
        // Get URL parameter
        $ID_carrera = trim($_GET["ID_carrera"]);

        // Prepare a select statement
        $sql = "SELECT * FROM carreras WHERE ID_carrera = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $ID_carrera;

            // Bind variables to the prepared statement as parameters
            if (is_int($param_id)) $__vartype = "i";
            elseif (is_string($param_id)) $__vartype = "s";
            elseif (is_numeric($param_id)) $__vartype = "d";
            else $__vartype = "b"; // blob
            mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    // Fetch result row as an associative array.
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $nombre = htmlspecialchars($row["nombre"]);

                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else {
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
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                            <label>Carrera</label>
                            <input type="text" name="nombre" maxlength="100" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                            <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
                        </div>

                        <input type="hidden" name="ID_carrera" value="<?php echo $ID_carrera; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="carreras-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
