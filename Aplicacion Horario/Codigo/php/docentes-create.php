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
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate nombre
    if(empty(trim($_POST["nombre"]))){
        $nombre_err = "Please enter a name.";
    } else{
        $nombre = strtoupper(trim($_POST["nombre"]));
        if(!preg_match("/^[a-zA-Z ]*$/", $nombre)){
            $nombre_err = "Only letters and white space allowed.";
        }
    }
    
    // Validate correo
    if(empty(trim($_POST["correo"]))){
        $correo_err = "Please enter an email.";
    } else{
        $correo = trim($_POST["correo"]);
    }
    
    // Validate estado
    if(empty(trim($_POST["estado"]))){
        $estado_err = "Please enter a state.";
    } else{
        $estado = trim($_POST["estado"]);
    }

    // Check input errors before inserting into database
    if(empty($nombre_err) && empty($correo_err) && empty($estado_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO docentes (nombre, correo, estado) VALUES (?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssi", $param_nombre, $param_correo, $param_estado);
            
            // Set parameters
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_estado = $estado;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to landing page
                header("location: docentes-index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Create Record</h2>
                    </div>
                    <p>Please fill this form and submit to add a record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                                <label>Nombre</label>
                                <input type="text" name="nombre" class="form-control" value="<?php echo $nombre; ?>">
                                <span class="form-text"><?php echo $nombre_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Correo</label>
                                <input type="text" name="correo" class="form-control" value="<?php echo $correo; ?>">
                                <span class="form-text"><?php echo $correo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                                <span class="form-text"><?php echo $estado_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="docentes-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>
