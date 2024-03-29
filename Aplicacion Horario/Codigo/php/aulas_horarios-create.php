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
$HORARIOS_DISPONIBLES_ID_horario_disponible = "";
$AULAS_DISPONIBLES_ID_aula_disponible = "";
$estado = "";
$dia = "";

$HORARIOS_DISPONIBLES_ID_horario_disponible_err = "";
$AULAS_DISPONIBLES_ID_aula_disponible_err = "";
$estado_err = "";
$dia_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $HORARIOS_DISPONIBLES_ID_horario_disponible = trim($_POST["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
    $AULAS_DISPONIBLES_ID_aula_disponible = trim($_POST["AULAS_DISPONIBLES_ID_aula_disponible"]);
    $estado = trim($_POST["estado"]);
    $dia = trim($_POST["dia"]);

    // Validate dia
    if(empty($dia)){
        $dia_err = "Por favor ingrese el día.";
    } else {
        // Check if the entered value is a valid weekday (from Monday to Friday)
        $dia_lowercase = strtolower($dia);
        if(!in_array($dia_lowercase, array('lunes', 'martes', 'miércoles', 'jueves', 'viernes'))) {
            $dia_err = "El día debe ser un día de la semana (lunes a viernes).";
        }
    }

    // Check if all errors are empty
    if(empty($HORARIOS_DISPONIBLES_ID_horario_disponible_err) && empty($AULAS_DISPONIBLES_ID_aula_disponible_err) && empty($estado_err) && empty($dia_err)){
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
          exit('Something weird happened'); //something a user can understand
        }

        $vars = parse_columns('aulas_horarios', $_POST);
        $stmt = $pdo->prepare("INSERT INTO aulas_horarios (HORARIOS_DISPONIBLES_ID_horario_disponible,AULAS_DISPONIBLES_ID_aula_disponible,estado,dia) VALUES (?,?,?,?)");

        if($stmt->execute([$HORARIOS_DISPONIBLES_ID_horario_disponible,$AULAS_DISPONIBLES_ID_aula_disponible,$estado,$dia])) {
            $stmt = null;
            header("location: aulas_horarios-index.php");
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
                            <label>Horario</label>
                            <select class="form-control" id="HORARIOS_DISPONIBLES_ID_horario_disponible" name="HORARIOS_DISPONIBLES_ID_horario_disponible">
                                <?php
                                    $sql = "SELECT hd.ID_horario_disponible, h.ID_horario, h.hora_inicio, h.hora_fin
                                            FROM horarios h
                                            INNER JOIN horarios_disponibles hd ON hd.HORARIOS_ID_horario = h.ID_horario";
                                           
                                    $result = mysqli_query($link, $sql);
                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        $value = $row['hora_inicio'] . " - " . $row['hora_fin']; // Formato para mostrar hora_inicio y hora_fin
                                        if ($row["ID_horario_disponible"] == $HORARIOS_DISPONIBLES_ID_horario_disponible){
                                            echo '<option value="' . $row["ID_horario_disponible"] . '" selected="selected">' . $value . '</option>';
                                        } else {
                                            echo '<option value="' . $row["ID_horario_disponible"] . '">' . $value . '</option>';
                                        }
                                    }
                                ?>
                            </select>
                            <span class="form-text"><?php echo $HORARIOS_DISPONIBLES_ID_horario_disponible_err; ?></span>
                        </div>

						<div class="form-group">
                                <label>Aula</label>
                                    <select class="form-control" id="AULAS_DISPONIBLES_ID_aula_disponible" name="AULAS_DISPONIBLES_ID_aula_disponible">
                                    <?php
                                    $sql = "SELECT ad.ID_aula_disponible, a.ID_aula, a.bloque, a.codigo
                                            FROM aulas a
                                            INNER JOIN aulas_disponibles ad ON ad.AULAS_ID_aula = a.ID_aula";
                                           
                                    $result = mysqli_query($link, $sql);
                                    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        $value = $row['bloque'] . " " . $row['codigo'];
                                            if ($row["ID_aula_disponible"] == $AULAS_DISPONIBLES_ID_aula_disponible){
                                            echo '<option value="' . "$row[ID_aula_disponible]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_aula_disponible]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $AULAS_DISPONIBLES_ID_aula_disponible_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Estado</label>
                                <input type="number" name="estado" class="form-control" value="<?php echo $estado; ?>">
                                <span class="form-text"><?php echo $estado_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Día</label>
                                <input type="text" name="dia" maxlength="45"class="form-control" value="<?php echo $dia; ?>">
                                <span class="form-text"><?php echo $dia_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="aulas_horarios-index.php" class="btn btn-secondary">Cancel</a>
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