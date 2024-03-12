<?php
// Check existence of id parameter before processing further
$_GET["ID_NRC"] = trim($_GET["ID_NRC"]);
if(isset($_GET["ID_NRC"]) && !empty($_GET["ID_NRC"])){
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Prepare a select statement
    $sql = "SELECT * FROM nrcs WHERE ID_NRC = ?";

    if($stmt = mysqli_prepare($link, $sql)){
        // Set parameters
        $param_id = trim($_GET["ID_NRC"]);

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
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }

        } else{
            echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>

                     <div class="form-group">
                            <h4>Asignatura</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["ASIGNATURAS_VIGENTES_ID_asignatura_vigente"]); ?></p>
                        </div><div class="form-group">
                            <h4>Docente</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["DOCENTES_ACTIVOS_ID_docente_activo"]); ?></p>
                        </div><div class="form-group">
                            <h4>Horario</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["HORARIOS_DISPONIBLES_ID_horario_disponible"]); ?></p>
                        </div><div class="form-group">
                            <h4>Carrera</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["CARRERAS_VIGENTES_ID_carrera_vigente"]); ?></p>
                        </div><div class="form-group">
                            <h4>Periodo</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["PERIODOS_ID_periodo"]); ?></p>
                        </div><div class="form-group">
                            <h4>NRC</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["codigo"]); ?></p>
                        </div><div class="form-group">
                            <h4>Nivel</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["nivel"]); ?></p>
                        </div><div class="form-group">
                            <h4>Horas semanales</h4>
                            <p class="form-control-static"><?php echo htmlspecialchars($row["horas_semanales"]); ?></p>
                        </div>

                    <p><a href="nrcs-index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>