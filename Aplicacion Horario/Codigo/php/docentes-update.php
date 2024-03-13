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
$nombre = $correo = $estado = "";
$nombre_err = $correo_err = $estado_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Nombre
    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingresa el nombre.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }

    // Validate Correo
    if (empty(trim($_POST["correo"]))) {
        $correo_err = "Por favor ingresa el correo.";
    } else {
        $correo = trim($_POST["correo"]);
    }

    // Validate Estado
    if (empty(trim($_POST["estado"]))) {
        $estado_err = "Por favor ingresa el estado.";
    } else {
        $estado = trim($_POST["estado"]);
    }

    // Check input errors before updating the database
    if (empty($nombre_err) && empty($correo_err) && empty($estado_err)) {
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

        $ID_docente = trim($_POST["ID_docente"]);
        $stmt = $pdo->prepare("UPDATE docentes SET nombre=?, correo=?, estado=? WHERE ID_docente=?");

        if ($stmt->execute([$nombre, $correo, $estado, $ID_docente])) {
            header("location: docentes-read.php?ID_docente=$ID_docente");
            exit();
        } else {
            echo "Something went wrong. Please try again later.";
            header("location: error.php");
            exit();
        }
    }
} else {
    // Check existence of id parameter before processing further
    $_GET["ID_docente"] = trim($_GET["ID_docente"]);
    if (isset($_GET["ID_docente"]) && !empty($_GET["ID_docente"])) {
        // Get URL parameter
        $ID_docente =  trim($_GET["ID_docente"]);

        // Prepare a select statement
        $sql = "SELECT * FROM docentes WHERE ID_docente = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Set parameters
            $param_id = $ID_docente;

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
                    $correo = htmlspecialchars($row["correo"]);
                    $estado = htmlspecialchars($row["estado"]);

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
                            <label>Nombre</label>
                            <input type="text" name="nombre" maxlength="100" class="form-control <?php echo (!empty($nombre_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nombre; ?>">
                            <span class="invalid-feedback"><?php echo $nombre_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Correo</label>
                            <input type="text" name="correo" maxlength="100" class="form-control <?php echo (!empty($correo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $correo; ?>">
                            <span class="invalid-feedback"><?php echo $correo_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" name="estado" class="form-control <?php echo (!empty($estado_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $estado; ?>">
                            <span class="invalid-feedback"><?php echo $estado_err; ?></span>
                        </div>
                        <input type="hidden" name="ID_docente" value="<?php echo $ID_docente; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="docentes-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
