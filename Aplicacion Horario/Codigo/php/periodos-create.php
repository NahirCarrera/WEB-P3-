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
$codigo = $nombre = $fecha_inicio = $fecha_fin = "";
$codigo_err = $nombre_err = $fecha_inicio_err = $fecha_fin_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate codigo
    if(empty(trim($_POST["codigo"]))) {
        $codigo_err = "Please enter a codigo.";
    } elseif(!ctype_digit($_POST["codigo"])) {
        $codigo_err = "Código must contain only numbers.";
    } else {
        $codigo = trim($_POST["codigo"]);
    }
    
    // Validate nombre
    if(empty(trim($_POST["nombre"]))) {
        $nombre_err = "Please enter a nombre.";     
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["nombre"]))) {
        $nombre_err = "Periodo must contain only letters.";
    } else {
        $nombre = trim($_POST["nombre"]);
    }
    
    // Validate fecha_inicio
    if(empty(trim($_POST["fecha_inicio"]))) {
        $fecha_inicio_err = "Please enter a fecha de inicio.";     
    } else {
        $fecha_inicio = trim($_POST["fecha_inicio"]);
    }
    
    // Validate fecha_fin
    if(empty(trim($_POST["fecha_fin"]))) {
        $fecha_fin_err = "Please enter a fecha de finalización.";     
    } else {
        $fecha_fin = trim($_POST["fecha_fin"]);
    }

    // Check input errors before inserting into database
    if(empty($codigo_err) && empty($nombre_err) && empty($fecha_inicio_err) && empty($fecha_fin_err)) {
        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false,
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Something weird happened');
        }

        $vars = parse_columns('periodos', $_POST);
        $stmt = $pdo->prepare("INSERT INTO periodos (codigo,nombre,fecha_inicio,fecha_fin) VALUES (?,?,?,?)");

        if($stmt->execute([$codigo, $nombre, $fecha_inicio, $fecha_fin])) {
            $stmt = null;
            header("location: periodos-index.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
    }
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
                            <label>Código</label>
                            <input type="text" name="codigo" maxlength="6" class="form-control" value="<?php echo htmlspecialchars($codigo); ?>">
                            <span class="form-text"><?php echo $codigo_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Periodo</label>
                            <input type="text" name="nombre" maxlength="45" class="form-control" value="<?php echo htmlspecialchars(strtoupper($nombre)); ?>">
                            <span class="form-text"><?php echo $nombre_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Fecha de inicio</label>
                            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
                            <span class="form-text"><?php echo $fecha_inicio_err; ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label>Fecha de finalización</label>
                            <input type="date" name="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($fecha_fin); ?>">
                            <span class="form-text"><?php echo $fecha_fin_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="periodos-index.php" class="btn btn-secondary">Cancel</a>
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
