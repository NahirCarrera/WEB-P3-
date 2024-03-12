<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$NRCS_ID_NRC = "";
$AULAS_HORARIOS_ID_aulas_horarios = "";

$NRCS_ID_NRC_err = "";
$AULAS_HORARIOS_ID_aulas_horarios_err = "";


// Processing form data when form is submitted
if(isset($_POST["ID_reserva"]) && !empty($_POST["ID_reserva"])){
    // Get hidden input value
    $ID_reserva = $_POST["ID_reserva"];

    $NRCS_ID_NRC = trim($_POST["NRCS_ID_NRC"]);
		$AULAS_HORARIOS_ID_aulas_horarios = trim($_POST["AULAS_HORARIOS_ID_aulas_horarios"]);
		

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

    $vars = parse_columns('reserva_aula', $_POST);
    $stmt = $pdo->prepare("UPDATE reserva_aula SET NRCS_ID_NRC=?,AULAS_HORARIOS_ID_aulas_horarios=? WHERE ID_reserva=?");

    if(!$stmt->execute([ $NRCS_ID_NRC,$AULAS_HORARIOS_ID_aulas_horarios,$ID_reserva  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: reserva_aula-read.php?ID_reserva=$ID_reserva");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["ID_reserva"] = trim($_GET["ID_reserva"]);
    if(isset($_GET["ID_reserva"]) && !empty($_GET["ID_reserva"])){
        // Get URL parameter
        $ID_reserva =  trim($_GET["ID_reserva"]);

        // Prepare a select statement
        $sql = "SELECT * FROM reserva_aula WHERE ID_reserva = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $ID_reserva;

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

                    $NRCS_ID_NRC = htmlspecialchars($row["NRCS_ID_NRC"]);
					$AULAS_HORARIOS_ID_aulas_horarios = htmlspecialchars($row["AULAS_HORARIOS_ID_aulas_horarios"]);
					

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
                                <label>NRC</label>
                                    <select class="form-control" id="NRCS_ID_NRC" name="NRCS_ID_NRC">
                                    <?php
                                        $sql = "SELECT *,ID_NRC FROM nrcs";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_NRC"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_NRC"] == $NRCS_ID_NRC){
                                            echo '<option value="' . "$row[ID_NRC]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_NRC]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $NRCS_ID_NRC_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Aula</label>
                                    <select class="form-control" id="AULAS_HORARIOS_ID_aulas_horarios" name="AULAS_HORARIOS_ID_aulas_horarios">
                                    <?php
                                        $sql = "SELECT *,ID_aulas_horarios FROM aulas_horarios";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_aulas_horarios"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_aulas_horarios"] == $AULAS_HORARIOS_ID_aulas_horarios){
                                            echo '<option value="' . "$row[ID_aulas_horarios]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_aulas_horarios]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $AULAS_HORARIOS_ID_aulas_horarios_err; ?></span>
                            </div>

                        <input type="hidden" name="ID_reserva" value="<?php echo $ID_reserva; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="reserva_aula-index.php" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
