<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$ASIGNATURAS_VIGENTES_ID_asignatura_vigente = "";
$DOCENTES_ACTIVOS_ID_docente_activo = "";
$HORARIOS_DISPONIBLES_ID_horario_disponible = "";
$CARRERAS_VIGENTES_ID_carrera_vigente = "";
$PERIODOS_ID_periodo = "";
$codigo = "";
$nivel = "";
$horas_semanales = "";

$ASIGNATURAS_VIGENTES_ID_asignatura_vigente_err = "";
$DOCENTES_ACTIVOS_ID_docente_activo_err = "";
$HORARIOS_DISPONIBLES_ID_horario_disponible_err = "";
$CARRERAS_VIGENTES_ID_carrera_vigente_err = "";
$PERIODOS_ID_periodo_err = "";
$codigo_err = "";
$nivel_err = "";
$horas_semanales_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $ASIGNATURAS_VIGENTES_ID_asignatura_vigente = trim($_POST["ASIGNATURAS_VIGENTES_ID_asignatura_vigente"]);
		$DOCENTES_ACTIVOS_ID_docente_activo = trim($_POST["DOCENTES_ACTIVOS_ID_docente_activo"]);
		$HORARIOS_DISPONIBLES_ID_horario_disponible = trim($_POST["HORARIOS_DISPONIBLES_ID_horario_disponible"]);
		$CARRERAS_VIGENTES_ID_carrera_vigente = trim($_POST["CARRERAS_VIGENTES_ID_carrera_vigente"]);
		$PERIODOS_ID_periodo = trim($_POST["PERIODOS_ID_periodo"]);
		$codigo = trim($_POST["codigo"]);
		$nivel = trim($_POST["nivel"]);
		$horas_semanales = trim($_POST["horas_semanales"]);
		

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

        $vars = parse_columns('nrcs', $_POST);
        $stmt = $pdo->prepare("INSERT INTO nrcs (ASIGNATURAS_VIGENTES_ID_asignatura_vigente,DOCENTES_ACTIVOS_ID_docente_activo,HORARIOS_DISPONIBLES_ID_horario_disponible,CARRERAS_VIGENTES_ID_carrera_vigente,PERIODOS_ID_periodo,codigo,nivel,horas_semanales) VALUES (?,?,?,?,?,?,?,?)");

        if($stmt->execute([ $ASIGNATURAS_VIGENTES_ID_asignatura_vigente,$DOCENTES_ACTIVOS_ID_docente_activo,$HORARIOS_DISPONIBLES_ID_horario_disponible,$CARRERAS_VIGENTES_ID_carrera_vigente,$PERIODOS_ID_periodo,$codigo,$nivel,$horas_semanales  ])) {
                $stmt = null;
                header("location: nrcs-index.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
                                <label>Asignatura</label>
                                    <select class="form-control" id="ASIGNATURAS_VIGENTES_ID_asignatura_vigente" name="ASIGNATURAS_VIGENTES_ID_asignatura_vigente">
                                    <?php
                                        $sql = "SELECT av.ID_asignatura_vigente, a.nombre FROM asignaturas a    
                                                INNER JOIN asignaturas_vigentes av ON a.ID_asignatura = av.ASIGNATURAS_ID_asignatura";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_asignatura_vigente"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_asignatura_vigente"] == $ASIGNATURAS_VIGENTES_ID_asignatura_vigente){
                                            echo '<option value="' . "$row[ID_asignatura_vigente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_asignatura_vigente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $ASIGNATURAS_VIGENTES_ID_asignatura_vigente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Docente</label>
                                    <select class="form-control" id="DOCENTES_ACTIVOS_ID_docente_activo" name="DOCENTES_ACTIVOS_ID_docente_activo">
                                    <?php
                                        $sql = "SELECT  da.ID_docente_activo, d.nombre FROM docentes d
                                        INNER JOIN docentes_activos da ON d.ID_docente = da.DOCENTES_ID_docente";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_docente_activo"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_docente_activo"] == $DOCENTES_ACTIVOS_ID_docente_activo){
                                            echo '<option value="' . "$row[ID_docente_activo]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_docente_activo]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $DOCENTES_ACTIVOS_ID_docente_activo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horario</label>
                                    <select class="form-control" id="HORARIOS_DISPONIBLES_ID_horario_disponible" name="HORARIOS_DISPONIBLES_ID_horario_disponible">
                                    <?php
                                        $sql = "SELECT hd.ID_horario_disponible, h.hora_inicio, h.hora_fin FROM horarios h
                                        INNER JOIN horarios_disponibles hd ON h.ID_horario = hd.HORARIOS_ID_horario";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_horario_disponible"]);
                                            $value = implode(" - ", $duprow);
                                            if ($row["ID_horario_disponible"] == $HORARIOS_DISPONIBLES_ID_horario_disponible){
                                            echo '<option value="' . "$row[ID_horario_disponible]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_horario_disponible]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $HORARIOS_DISPONIBLES_ID_horario_disponible_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Carrera</label>
                                    <select class="form-control" id="CARRERAS_VIGENTES_ID_carrera_vigente" name="CARRERAS_VIGENTES_ID_carrera_vigente">
                                    <?php
                                        $sql = "SELECT cv.ID_carrera_vigente, c.nombre FROM carreras c
                                        INNER JOIN carreras_vigentes cv ON c.ID_carrera = cv.CARRERAS_ID_carrera";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_carrera_vigente"]);
                                            $value = implode(" | ", $duprow);
                                            if ($row["ID_carrera_vigente"] == $CARRERAS_VIGENTES_ID_carrera_vigente){
                                            echo '<option value="' . "$row[ID_carrera_vigente]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_carrera_vigente]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $CARRERAS_VIGENTES_ID_carrera_vigente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Periodo</label>
                                    <select class="form-control" id="PERIODOS_ID_periodo" name="PERIODOS_ID_periodo">
                                    <?php
                                        $sql = "SELECT ID_periodo, codigo, nombre FROM periodos";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            $duprow = $row;
                                            unset($duprow["ID_periodo"]);
                                            $value = implode("  ", $duprow);
                                            if ($row["ID_periodo"] == $PERIODOS_ID_periodo){
                                            echo '<option value="' . "$row[ID_periodo]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[ID_periodo]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $PERIODOS_ID_periodo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>NRC</label>
                                <input type="number" name="codigo" class="form-control" value="<?php echo $codigo; ?>">
                                <span class="form-text"><?php echo $codigo_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Nivel</label>
                                <input type="number" name="nivel" class="form-control" value="<?php echo $nivel; ?>" max = 10 min = 1>
                                <span class="form-text"><?php echo $nivel_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>Horas semanales</label>
                                <input type="number" name="horas_semanales" class="form-control" value="<?php echo $horas_semanales; ?>" max = 10 min=1>
                                <span class="form-text"><?php echo $horas_semanales_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="nrcs-index.php" class="btn btn-secondary">Cancel</a>
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