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
// Incluir la clase de conexión
include_once('mySqli.php');

// Instanciar la clase de conexión
$db = new Conect_MySqli();

// Query para obtener los períodos
$query = "SELECT nombre FROM periodos";

// Ejecutar la consulta
$result = $db->execute($query);

// Verificar si se encontraron resultados
if ($db->get_num_rows($result) > 0) {
    // Generar las opciones del menú desplegable
    while ($row = $db->fetch_row($result)) {
        echo "<a class='dropdown-item' href='#'>" . $row['nombre'] . "</a>";
    }
} else {
    // Si no se encontraron resultados
    echo "<a class='dropdown-item' href='#'>No hay períodos disponibles</a>";
}

// Liberar el resultado y cerrar la conexión
$db->free_result($result);
$db->close_db();
?>
