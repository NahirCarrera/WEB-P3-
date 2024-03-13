<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$codigo = $nombre = "";
$codigo_err = $nombre_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Código
    if (empty(trim($_POST["codigo"]))) {
        $codigo_err = "Por favor ingresa el código.";
    } else {
        $codigo = trim($_POST["codigo"]);
    }

    // Validate Nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingresa el nombre del departamento.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Check input errors before updating the database
    if (empty($codigo_err) && empty($nombre_err)) {
        // Get hidden input value
        $ID_departamento = $_POST["ID_departamento"];

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

        $vars = parse_columns('departamentos', $_POST);
        $stmt = $pdo->prepare("UPDATE departamentos SET codigo=?, nombre=? WHERE ID_departamento=?");

        if (!$stmt->execute([$codigo, $nombre, $ID_departamento])) {
            echo "Something went wrong. Please try again later.";
            header("location: error.php");
            exit();
        } else {
            $stmt = null;
            header("location: departamentos-read.php?ID_departamento=$ID_departamento");
            exit();
        }
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["ID_departamento"] = trim($_GET["ID_departamento"]);
    if (isset($_GET["ID_departamento"]) && !empty($_GET["ID_departamento"])) {
        // Get URL parameter
        $ID_departamento = trim($_GET["ID_departamento"]);

        // Prepare a select statement
        $sql = "SELECT * FROM departamentos WHERE ID_departamento = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $ID_departamento;

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
                    $codigo = htmlspecialchars($row["codigo"]);
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
                                <label>Código</label>
                                <input type="text" name="codigo" maxlength="10" class="form-control <?php echo (!empty($codigo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $codigo; ?>">
                                <span class="invalid-feedback"><?php echo $codigo_err; ?></span>
                            </div>
                        <div class="form-group">
                                <label>Departamento</label>
                                <input type="text" name="nombre" maxlength="100" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                                <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_departamento" value="<?php echo $ID_departamento; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="departamentos-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
