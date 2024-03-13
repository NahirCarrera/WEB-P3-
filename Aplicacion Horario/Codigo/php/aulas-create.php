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
$codigo = "";
$bloque = "";
$piso = "";

$codigo_err = "";
$bloque_err = "";
$piso_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate codigo
    if(empty(trim($_POST["codigo"]))){
        $codigo_err = "Por favor ingrese el código del aula.";
    } else {
        $codigo = trim($_POST["codigo"]);
    }

    // Validate bloque
    if(empty(trim($_POST["bloque"]))){
        $bloque_err = "Por favor ingrese el bloque.";
    } else {
        $bloque = trim($_POST["bloque"]);
        // Check if the block is a single uppercase letter from A to Z
        if(!preg_match("/^[A-Z]$/", $bloque)){
            $bloque_err = "El bloque debe ser una letra mayúscula de la A a la Z.";
        }
    }

    // Validate piso
    if(empty(trim($_POST["piso"]))){
        $piso_err = "Por favor ingrese el piso.";
    } else {
        $piso = trim($_POST["piso"]);
    }

    // Check if all errors are empty
    if(empty($codigo_err) && empty($bloque_err) && empty($piso_err)){
        // Additional validations
        if(!(($codigo >= 201 && $codigo <= 209) || ($codigo >= 301 && $codigo <= 309))){
            $codigo_err = "El código del aula debe estar entre 201-209 o 301-309.";
        }

        // Processing data after validation
        if(empty($codigo_err)){
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

            $vars = parse_columns('aulas', $_POST);
            $stmt = $pdo->prepare("INSERT INTO aulas (codigo,bloque,piso) VALUES (?,?,?)");

            if($stmt->execute([$codigo, $bloque, $piso])) {
                $stmt = null;
                header("location: aulas-index.php");
            } else {
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
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

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="aulas-index.php" class="btn btn-secondary">Cancel</a>
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