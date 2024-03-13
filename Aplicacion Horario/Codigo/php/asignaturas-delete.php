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
<?php
// Process delete operation after confirmation
if(isset($_POST["ID_asignatura"]) && !empty($_POST["ID_asignatura"])){
    // Include config file
    require_once "config.php";
    require_once "helpers.php";

    // Set ID_asignatura variable
    $id_asignatura = trim($_POST["ID_asignatura"]);

    // Attempt to delete records from reserva_aula table first
    $sql_delete_reserva_aula = "DELETE FROM reserva_aula WHERE NRCS_ID_NRC IN (SELECT ID_NRC FROM nrcs WHERE ASIGNATURAS_VIGENTES_ID_asignatura_vigente IN (SELECT ID_asignatura_vigente FROM asignaturas_vigentes WHERE ASIGNATURAS_ID_asignatura = ?))";
    if($stmt_delete_reserva_aula = mysqli_prepare($link, $sql_delete_reserva_aula)){
        // Bind variable to the prepared statement as parameter
        mysqli_stmt_bind_param($stmt_delete_reserva_aula, "i", $id_asignatura);

        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt_delete_reserva_aula)){
            // Free the statement
            mysqli_stmt_close($stmt_delete_reserva_aula);
            
            // Then delete records from nrcs table
            $sql_delete_nrcs = "DELETE FROM nrcs WHERE ASIGNATURAS_VIGENTES_ID_asignatura_vigente IN (SELECT ID_asignatura_vigente FROM asignaturas_vigentes WHERE ASIGNATURAS_ID_asignatura = ?)";
            if($stmt_delete_nrcs = mysqli_prepare($link, $sql_delete_nrcs)){
                // Bind variable to the prepared statement as parameter
                mysqli_stmt_bind_param($stmt_delete_nrcs, "i", $id_asignatura);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt_delete_nrcs)){
                    // Free the statement
                    mysqli_stmt_close($stmt_delete_nrcs);
                    
                    // Then delete records from asignaturas_vigentes table
                    $sql_delete_asignaturas_vigentes = "DELETE FROM asignaturas_vigentes WHERE ASIGNATURAS_ID_asignatura = ?";
                    if($stmt_delete_asignaturas_vigentes = mysqli_prepare($link, $sql_delete_asignaturas_vigentes)){
                        // Bind variable to the prepared statement as parameter
                        mysqli_stmt_bind_param($stmt_delete_asignaturas_vigentes, "i", $id_asignatura);

                        // Attempt to execute the prepared statement
                        if(mysqli_stmt_execute($stmt_delete_asignaturas_vigentes)){
                            // Then delete record from asignaturas table
                            $sql_delete_asignatura = "DELETE FROM asignaturas WHERE ID_asignatura = ?";
                            if($stmt_delete_asignatura = mysqli_prepare($link, $sql_delete_asignatura)){
                                // Bind variable to the prepared statement as parameter
                                mysqli_stmt_bind_param($stmt_delete_asignatura, "i", $id_asignatura);

                                // Attempt to execute the prepared statement
                                if(mysqli_stmt_execute($stmt_delete_asignatura)){
                                    // Records deleted successfully. Redirect to landing page
                                    header("location: asignaturas-index.php");
                                    exit();
                                } else{
                                    echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
                                }
                            } else{
                                echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
            }
        } else{
            echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
        }
    } else{
        echo "Oops! Something went wrong. Please try again later.<br>".mysqli_error($link);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter
    if(empty($_GET["ID_asignatura"])){
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
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h1>Delete Record</h1>
                    </div>
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div class="alert alert-danger fade-in">
                            <input type="hidden" name="ID_asignatura" value="<?php echo trim($_GET["ID_asignatura"]); ?>"/>
                            <p>Are you sure you want to delete this record?</p><br>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="asignaturas-index.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
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
</body>
</html>
