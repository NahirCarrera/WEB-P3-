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
                        <h2 class="float-left">Asignaturas</h2>
                        <a href="asignaturas-create.php" class="btn btn-success float-right">Agregar</a>
                        <a href="asignaturas-index.php" class="btn btn-info float-right mr-2">Refrescar</a>
                        <a href="index1.php" class="btn btn-secondary float-right mr-2">Atrás</a>
                    </div>

                    <div class="form-row">
                        <form action="asignaturas-index.php" method="get">
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

                    $total_pages_sql = "SELECT COUNT(*) FROM asignaturas";
                    $result = mysqli_query($link,$total_pages_sql);
                    $total_rows = mysqli_fetch_array($result)[0];
                    $total_pages = ceil($total_rows / $no_of_records_per_page);

                    //Column sorting on column name
                    $orderBy = array('DEPARTAMENTOS_ID_departamento', 'codigo', 'nombre');
                    $order = 'ID_asignatura';
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
                    $sql = "SELECT * FROM asignaturas ORDER BY $order $sort LIMIT $offset, $no_of_records_per_page";
                    $count_pages = "SELECT * FROM asignaturas";


                    if(!empty($_GET['search'])) {
                        $search = ($_GET['search']);
                        $sql = "SELECT * FROM asignaturas
                            WHERE CONCAT_WS (DEPARTAMENTOS_ID_departamento,codigo,nombre)
                            LIKE '%$search%'
                            ORDER BY $order $sort
                            LIMIT $offset, $no_of_records_per_page";
                        $count_pages = "SELECT * FROM asignaturas
                            WHERE CONCAT_WS (DEPARTAMENTOS_ID_departamento,codigo,nombre)
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
                                        echo "<th><a href=?search=$search&sort=&order=DEPARTAMENTOS_ID_departamento&sort=$sort>Departamento</th>";
										echo "<th><a href=?search=$search&sort=&order=codigo&sort=$sort>Código</th>";
										echo "<th><a href=?search=$search&sort=&order=nombre&sort=$sort>Asignatura</th>";
										
                                        echo "<th>Accción</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['DEPARTAMENTOS_ID_departamento']) . "</td>";echo "<td>" . htmlspecialchars($row['codigo']) . "</td>";echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                        echo "<td>";
                                            echo "<a href='asignaturas-read.php?ID_asignatura=". $row['ID_asignatura'] ."' title='View Record' data-toggle='tooltip'><i class='far fa-eye'></i></a>";
                                            echo "<a href='asignaturas-update.php?ID_asignatura=". $row['ID_asignatura'] ."' title='Update Record' data-toggle='tooltip'><i class='far fa-edit'></i></a>";
                                            echo "<a href='asignaturas-delete.php?ID_asignatura=". $row['ID_asignatura'] ."' title='Delete Record' data-toggle='tooltip'><i class='far fa-trash-alt'></i></a>";
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
     <!-- Script para mantener el período seleccionado -->
     <script>
    $(document).ready(function(){
      // Cargar los períodos dinámicamente al cargar la página
      $.get("get_periodos.php", function(data){
        $("#periodDropdownMenu").html(data);
        
        // Obtener el período seleccionado de la URL y establecerlo como seleccionado en el menú
        var urlParams = new URLSearchParams(window.location.search);
        var selectedPeriod = urlParams.get('period');
        if (selectedPeriod) {
          $("#periodDropdown").text(selectedPeriod);
          $("#periodDropdownMenu .dropdown-item").removeClass("active");
          $("#periodDropdownMenu .dropdown-item:contains('" + selectedPeriod + "')").addClass("active");
        }
      });

      // Manejar clic en elemento del menú desplegable de períodos
      $(document).on("click", "#periodDropdownMenu .dropdown-item", function(){
        var selectedPeriod = $(this).text();
        $("#periodDropdown").text(selectedPeriod);
        $(this).addClass("active").siblings().removeClass("active");
        $("#periodDropdownMenu").removeClass("show"); // Cerrar el menú desplegable de períodos
        $("#periodDropdownMenu").css("display", "none"); // Cambiar el estilo a display: none

        // Actualizar el período seleccionado en los enlaces de página
        $(".dropdown-menu a.dropdown-item").each(function() {
          var pageLink = $(this);
          var href = pageLink.attr("href");
          var newHref = href.split("?")[0] + "?period=" + encodeURIComponent(selectedPeriod);
          pageLink.attr("href", newHref);
        });
      });

    });
    </script>
    
    <script type="text/javascript">
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>
</html>