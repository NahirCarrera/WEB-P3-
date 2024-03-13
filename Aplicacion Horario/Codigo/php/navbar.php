<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navbar con Botón de Salida</title>
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand nav-link disabled" href="#">Horario</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Select Page
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a href="asignaturas-index.php" class="dropdown-item">Agregar Asignaturas</a>                                                                                                                                                                                                
          <a class="dropdown-item" href="asignaturas_vigentes-index.php">Relacionar  Asignaturas - Periodos</a>
          <a class="dropdown-item" href="aulas-index.php">Agregar Aulas</a>
          <a class="dropdown-item" href="aulas_disponibles-index.php">Relacionar Aulas - Periodos</a>
          <a class="dropdown-item" href="aulas_horarios-index.php">Relacionar Aulas - Periodos - Días</a>
          <a class="dropdown-item" href="carreras-index.php">Agregar Carreras</a>
          <a class="dropdown-item" href="carreras_vigentes-index.php">Relacionar Carreras - Periodos</a>
          <a class="dropdown-item" href="departamentos-index.php">Agregar Departamentos</a>
          <a class="dropdown-item" href="docentes-index.php">Agregar Docentes</a>
          <a class="dropdown-item" href="docentes_activos-index.php">Relacionar Docentes -  Periodos</a>
          <a class="dropdown-item" href="horarios-index.php">Agregar Horarios</a>
          <a class="dropdown-item" href="horarios_disponibles-index.php">Relacionar Horarios Periodos</a>
          <a class="dropdown-item" href="nrcs-index.php">Agregar Cursos</a>
          <a class="dropdown-item" href="perfiles-index.php">Agregar Perfiles</a>
          <a class="dropdown-item" href="periodos-index.php">Agregar Periodos</a>
          <a class="dropdown-item" href="reserva_aula-index.php">Reservar Aula</a>
          <a class="dropdown-item" href="usuarios-index.php">Agregar Usuario</a>
          <a class="dropdown-item" href="novedades-index.php">Novedades</a>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" href="logout.php">
          <i class="fas fa-sign-out-alt"></i> Salir
        </a>
      </li>
    </ul>
  </div>
</nav>

<!-- Bootstrap y jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>

<!-- JavaScript para prevenir el comportamiento predeterminado del enlace -->
<script>
  $(document).ready(function(){
    $('.dropdown-toggle').click(function(e){
      e.preventDefault();
      $(this).next('.dropdown-menu').slideToggle();
    });
  });
</script>

</body>
</html>
