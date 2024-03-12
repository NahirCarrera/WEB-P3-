<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$nombre = "";
$contraseña = "";
$PERFILES_ID_perfil = "";

$nombre_err = "";
$contraseña_err = "";
$PERFILES_ID_perfil_err = "";


// Processing form data when form is submitted
if(isset($_POST["PERFILES_ID_perfil"]) && !empty($_POST["PERFILES_ID_perfil"])){
    // Get hidden input value
    $PERFILES_ID_perfil = $_POST["PERFILES_ID_perfil"];

    $nombre = trim($_POST["nombre"]);
		$contraseña = trim($_POST["contraseña"]);
		$PERFILES_ID_perfil = trim($_POST["PERFILES_ID_perfil"]);
		

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

    $vars = parse_columns('usuarios', $_POST);
    $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?,contraseña=?,PERFILES_ID_perfil=? WHERE PERFILES_ID_perfil=?");

    if(!$stmt->execute([ $nombre,$contraseña,$PERFILES_ID_perfil,$PERFILES_ID_perfil  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: usuarios-read.php?PERFILES_ID_perfil=$PERFILES_ID_perfil");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["PERFILES_ID_perfil"] = trim($_GET["PERFILES_ID_perfil"]);
    if(isset($_GET["PERFILES_ID_perfil"]) && !empty($_GET["PERFILES_ID_perfil"])){
        // Get URL parameter
        $PERFILES_ID_perfil =  trim($_GET["PERFILES_ID_perfil"]);

        // Prepare a select statement
        $sql = "SELECT * FROM usuarios WHERE PERFILES_ID_perfil = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $PERFILES_ID_perfil;

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

                    $nombre = htmlspecialchars($row["nombre"]);
					$contraseña = htmlspecialchars($row["contraseña"]);
					$PERFILES_ID_perfil = htmlspecialchars($row["PERFILES_ID_perfil"]);
					

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
                                <label>Nombre de usuario</label>
                                <input type="text" name="nombre" maxlength="45"class="form-control" value="<?php echo $nombre; ?>">
                                <span class="form-text"><?php echo $nombre_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Contraseña</label>
                                <input type="text" name="contraseña" maxlength="100"class="form-control" value="<?php echo $contraseña; ?>">
                                <span class="form-text"><?php echo $contraseña_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Perfil</label>
                                    <select class="form-control" id="PERFILES_ID_perfil" name="PERFILES_ID_perfil">
                                    <?php
                                        $sql = "SELECT *,ID_perfil FROM perfiles";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_perfil"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_perfil"] == $PERFILES_ID_perfil){
                                            echo '<option value="' . "$row[ID_perfil]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_perfil]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $PERFILES_ID_perfil_err; ?></span>
                            </div>

                        <input type="hidden" name="PERFILES_ID_perfil" value="<?php echo $PERFILES_ID_perfil; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="usuarios-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
