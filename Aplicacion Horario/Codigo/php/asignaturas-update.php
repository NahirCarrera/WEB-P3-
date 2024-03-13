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

// Define una función para validar el formato del código
function validar_codigo($codigo) {
    // Verifica si el código tiene el formato adecuado (A seguido de 4 números)
    return preg_match('/^[A-Z]\d{4}$/', $codigo);
}

// Define una función para validar que la asignatura solo contenga letras mayúsculas
function validar_asignatura($nombre) {
    // Verifica si la asignatura contiene solo letras mayúsculas
    return ctype_upper($nombre);
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valida que todos los campos estén llenos
    if (empty(trim($_POST["DEPARTAMENTOS_ID_departamento"]))) {
        $DEPARTAMENTOS_ID_departamento_err = "Por favor seleccione un departamento.";
    } else {
        $DEPARTAMENTOS_ID_departamento = trim($_POST["DEPARTAMENTOS_ID_departamento"]);
    }

    if (empty(trim($_POST["codigo"]))) {
        $codigo_err = "Por favor ingrese el código.";
    } else {
        $codigo = trim($_POST["codigo"]);
        // Validar el formato del código
        if (!validar_codigo($codigo)) {
            $codigo_err = "El código debe tener el formato A seguido de 4 números.";
        }
    }

    if (empty(trim($_POST["nombre"]))) {
        $nombre_err = "Por favor ingrese el nombre de la asignatura.";
    } else {
        $nombre = trim($_POST["nombre"]);
        // Validar que la asignatura solo contenga letras mayúsculas
        if (!validar_asignatura($nombre)) {
            $nombre_err = "El nombre de la asignatura debe contener solo letras mayúsculas.";
        }
    }

    // Verifica si hay errores en cualquier campo
    if (empty($DEPARTAMENTOS_ID_departamento_err) && empty($codigo_err) && empty($nombre_err)) {
        // Si no hay errores, procede a insertar los datos en la base de datos
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

        $vars = parse_columns('asignaturas', $_POST);
        $stmt = $pdo->prepare("INSERT INTO asignaturas (DEPARTAMENTOS_ID_departamento,codigo,nombre) VALUES (?,?,?)");

        if($stmt->execute([ $DEPARTAMENTOS_ID_departamento,$codigo,$nombre  ])) {
            $stmt = null;
            header("location: asignaturas-index.php");
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
                            <input type="text" name="codigo" maxlength="10" class="form-control" value="<?php echo $codigo; ?>">
                            <span class="form-text"><?php echo $codigo_err; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Asignatura</label>
                            <input type="text" name="nombre" maxlength="100" class="form-control" value="<?php echo $nombre; ?>">
                            <span class="form-text"><?php echo $nombre_err; ?></span>
                        </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="asignaturas-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
