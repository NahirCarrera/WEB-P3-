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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Horario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/6b773fe9e4.js" crossorigin="anonymous"></script>
    <style type="text/css">
        .page-header h2{
            margin-top: 0;
        }
        table tr td:last-child a{
            margin-right: 5px;
        }
        body {
            font-size: 14px;
        }
    </style>
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header clearfix">
                        <h2 class="float-left">Cursos</h2>
                        <a href="nrcs-create.php" class="btn btn-success float-right">Agregar</a>
                        <a href="nrcs-index.php" class="btn btn-info float-right mr-2">Refrescar</a>
                        <a href="index1.php" class="btn btn-secondary float-right mr-2">Atrás</a>
                    </div>

                    <div class="form-row">
                        <form action="nrcs-index.php" method="get">
                        <div class="col">
                          <input type="text" class="form-control" placeholder="Buscar" name="search">
                        </div>
                    </div>
                        </form>
                    <br>

                    <?php
                    // Include config file
                    require_once "config.php";
                    require_once "helpers.php";

                    //Get current URL and parameters for correct pagination
                    $domain   = $_SERVER['HTTP_HOST'];
                    $script   = $_SERVER['SCRIPT_NAME'];
                    $parameters   = $_GET ? $_SERVER['QUERY_STRING'] : "" ;
                    $protocol=($_SERVER['HTTPS'] == "on" ? "https" : "http");
                    $currenturl = $protocol . '://' . $domain. $script . '?' . $parameters;

                    //Pagination
                    if (isset($_GET['pageno'])) {
                        $pageno = $_GET['pageno'];
                    } else {
                        $pageno = 1;
                    }

                    //$no_of_records_per_page is set on the index page. Default is 10.
                    $offset = ($pageno-1) * $no_of_records_per_page;

                    $total_pages_sql = "SELECT COUNT(*) FROM nrcs";
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
                    $orderBy = array('ASIGNATURAS_VIGENTES_ID_asignatura_vigente', 'DOCENTES_ACTIVOS_ID_docente_activo', 'HORARIOS_DISPONIBLES_ID_horario_disponible', 'CARRERAS_VIGENTES_ID_carrera_vigente', 'PERIODOS_ID_periodo', 'codigo', 'nivel', 'horas_semanales');
                    $order = 'ID_NRC';
                    if (isset($_GET['order']) && in_array($_GET['order'], $orderBy)) {
                            $order = $_GET['order'];
                        }

                    //Column sort order
                    $sortBy = array('asc', 'desc'); $sort = 'desc';
                    if (isset($_GET['sort']) && in_array($_GET['sort'], $sortBy)) {
                          if($_GET['sort']=='asc') {
                            $sort='desc';
                            }
                    else {
                        $sort='asc';
                        }
                    }

                    // Attempt select query execution
                    $sql = "SELECT * FROM nrcs ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM nrcs";


                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT * FROM nrcs
                            WHERE CONCAT_WS (ASIGNATURAS_VIGENTES_ID_asignatura_vigente,DOCENTES_ACTIVOS_ID_docente_activo,HORARIOS_DISPONIBLES_ID_horario_disponible,CARRERAS_VIGENTES_ID_carrera_vigente,PERIODOS_ID_periodo,codigo,nivel,horas_semanales)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT * FROM nrcs
                            WHERE CONCAT_WS (ASIGNATURAS_VIGENTES_ID_asignatura_vigente,DOCENTES_ACTIVOS_ID_docente_activo,HORARIOS_DISPONIBLES_ID_horario_disponible,CARRERAS_VIGENTES_ID_carrera_vigente,PERIODOS_ID_periodo,codigo,nivel,horas_semanales)
                            LIKE '%$search%'
                            ORDER BY $order $sort";
                    }
                    else {
                        $search = "";
                    }

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            if ($result_count = mysqli_query($link, $count_pages)) {
                               $total_pages = ceil(mysqli_num_rows($result_count) / $no_of_records_per_page);
                           }
                            $number_of_results = mysqli_num_rows($result_count);
                            echo " " . $number_of_results . " resultados - Pag. " . $pageno . " de " . $total_pages;

                            echo "<table class='table table-bordered table-striped'>";
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th><a href=?search=$search&sort=&order=ASIGNATURAS_VIGENTES_ID_asignatura_vigente&sort=$sort>Asignatura</th>";
										echo "<th><a href=?search=$search&sort=&order=DOCENTES_ACTIVOS_ID_docente_activo&sort=$sort>Docente</th>";
										echo "<th><a href=?search=$search&sort=&order=HORARIOS_DISPONIBLES_ID_horario_disponible&sort=$sort>Horario</th>";
										echo "<th><a href=?search=$search&sort=&order=CARRERAS_VIGENTES_ID_carrera_vigente&sort=$sort>Carrera</th>";
										echo "<th><a href=?search=$search&sort=&order=PERIODOS_ID_periodo&sort=$sort>Periodo</th>";
										echo "<th><a href=?search=$search&sort=&order=codigo&sort=$sort>NRC</th>";
										echo "<th><a href=?search=$search&sort=&order=nivel&sort=$sort>Nivel</th>";
										echo "<th><a href=?search=$search&sort=&order=horas_semanales&sort=$sort>Horas semanales</th>";
										
                                        echo "<th>Acción</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['ASIGNATURAS_VIGENTES_ID_asignatura_vigente']) . "</td>";echo "<td>" . htmlspecialchars($row['DOCENTES_ACTIVOS_ID_docente_activo']) . "</td>";echo "<td>" . htmlspecialchars($row['HORARIOS_DISPONIBLES_ID_horario_disponible']) . "</td>";echo "<td>" . htmlspecialchars($row['CARRERAS_VIGENTES_ID_carrera_vigente']) . "</td>";echo "<td>" . htmlspecialchars($row['PERIODOS_ID_periodo']) . "</td>";echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";echo "<td>" . htmlspecialchars($row['nivel']) . "</td>";echo "<td>" . htmlspecialchars($row['horas_semanales']) . "</td>";
                                        echo "<td>";
                                            echo "<a href='nrcs-read.php?ID_NRC=". $row['ID_NRC'] ."' title='View Record' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                                            echo "<a href='nrcs-update.php?ID_NRC=". $row['ID_NRC'] ."' title='Update Record' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                                            echo "<a href='nrcs-delete.php?ID_NRC=". $row['ID_NRC'] ."' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";
                            echo "</table>";
?>
                                <ul class="pagination" align-right>
                                <?php
                                    $new_url = preg_replace('/&?pageno=[^&]*/', '', $currenturl);
                                 ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=1' ?>">Primero</a></li>
                                    <li class="page-item <?php if($pageno <= 1){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo $new_url ."&pageno=".($pageno - 1); } ?>">Prev</a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo $new_url . "&pageno=".($pageno + 1); } ?>">Siguiente</a>
                                    </li>
                                    <li class="page-item <?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
                                        <a class="page-item"><a class="page-link" href="<?php echo $new_url .'&pageno=' . $total_pages; ?>">Último</a>
                                    </li>
                                </ul>
<?php
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo "<p class='lead'><em>No records were found.</em></p>";
                        }
                    } else{
                        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
                    }

                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</body>
</html>