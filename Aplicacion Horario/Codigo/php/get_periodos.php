<?php
// Include config file
require_once "config.php";

try {
    // Establecer conexión PDO
    $pdo = new PDO("mysql:host=$db_server;dbname=$db_name;charset=utf8mb4", $db_user, $db_password);
    
    // Habilitar excepciones PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Consulta para obtener los períodos
    $sql = "SELECT ID_periodo, nombre FROM periodos";
    
    // Preparar la consulta
    $stmt = $pdo->prepare($sql);
    
    // Ejecutar la consulta
    $stmt->execute();
    
    // Verificar si se encontraron resultados
    if ($stmt->rowCount() > 0) {
        // Generar las opciones del menú desplegable
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<a class='dropdown-item' href='#' data-id='" . $row['ID_periodo'] . "'>" . $row['nombre'] . "</a>";
        }
    } else {
        // Si no se encontraron resultados
        echo "<a class='dropdown-item' href='#'>No hay períodos disponibles</a>";
    }
} catch(PDOException $e) {
    // En caso de error, mostrar mensaje de error
    echo "Error: " . $e->getMessage();
}

// Cerrar conexión
unset($pdo);
?>
